<?php
session_start();
require_once '../../../Koneksi-DSA/Koneksi-DSA.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   try {
       $keyLength = $_POST['keyLength'];
       $keyCode = $_POST['keyCode'];

       // Validate inputs
       if (!is_numeric($keyLength)) {
           throw new Exception("Panjang kunci harus berupa angka.");
       }

       $validDSALengths = [1024, 2048, 3072];
       if (!in_array((int)$keyLength, $validDSALengths)) {
           throw new Exception("Panjang kunci DSA harus 1024, 2048, atau 3072 bit");
       }

       if (!preg_match('/^\d{3} - [A-Za-z]{1,3}$/', $keyCode)) {
           throw new Exception("Format kode kunci tidak valid! Harus berupa 3 digit angka diikuti dengan 1-3 huruf.");
       }

       // Check if key code exists
       $stmt = $koneksi->prepare("SELECT COUNT(*) FROM ds_kunci_tb WHERE kode_kunci = ?");
       if (!$stmt) {
           throw new Exception("Prepare failed: " . $koneksi->error); 
       }
       $stmt->bind_param("s", $keyCode);
       $stmt->execute();
       $stmt->bind_result($count);
       $stmt->fetch();
       $stmt->close();

       if ($count > 0) {
           throw new Exception("Kode kunci sudah ada. Silakan pilih kode kunci lain.");
       }

       // Generate unique key ID
       $keyId = 'KY-' . bin2hex(random_bytes(3)) . '-' . 
               substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);

       // Setup key directory
       $keyDir = './Keys/';
       if (!is_dir($keyDir)) {
           if (!mkdir($keyDir, 0700, true)) {
               throw new Exception("Gagal membuat folder kunci.");
           }
       }

       $privateKeyFile = $keyDir . $keyId . '_private_key.pem';
       $publicKeyFile = $keyDir . $keyId . '_public_key.pem';
       $dsaParamFile = $keyDir . $keyId . '_dsa_params.pem';

       // Generate DSA keys using OpenSSL
       $commands = [
           "openssl dsaparam -out $dsaParamFile $keyLength",
           "openssl gendsa -out $privateKeyFile $dsaParamFile",
           "openssl dsa -pubout -in $privateKeyFile -out $publicKeyFile"
       ];

       foreach ($commands as $cmd) {
           exec($cmd, $output, $returnVar);
           if ($returnVar !== 0) {
               throw new Exception("Gagal menjalankan perintah OpenSSL: $cmd");
           }
       }

       // Set file permissions
       chmod($privateKeyFile, 0600);
       chmod($publicKeyFile, 0644);

       // Read and format keys
       $privateKey = file_get_contents($privateKeyFile);
       $publicKey = file_get_contents($publicKeyFile);
       
       if ($privateKey === false || $publicKey === false) {
           throw new Exception("Gagal membaca file kunci");
       }

       $privateKey = str_replace(
           ['-----BEGIN DSA PRIVATE KEY-----', '-----END DSA PRIVATE KEY-----', "\n"],
           '',
           $privateKey
       );
       $publicKey = str_replace(
           ['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n"],
           '',
           $publicKey
       );

       // Database transaction
       $koneksi->begin_transaction();
       
       $stmt = $koneksi->prepare(
           "INSERT INTO ds_kunci_tb (id_kunci, panjang_kunci, kode_kunci, kunci_privat, kunci_publik) 
            VALUES (?, ?, ?, ?, ?)"
       );
       
       if (!$stmt) {
           throw new Exception("Prepare failed: " . $koneksi->error);
       }

       $stmt->bind_param("sssss", $keyId, $keyLength, $keyCode, $privateKey, $publicKey);
       
       if (!$stmt->execute()) {
           throw new Exception("Execute failed: " . $stmt->error);
       }

       $koneksi->commit();
       
       // Cleanup
       unlink($dsaParamFile);
       unlink($privateKeyFile);
       unlink($publicKeyFile);

       $_SESSION['success_key'] = "Kunci DSA berhasil dibuat dengan kode: $keyCode";

   } catch (Exception $e) {
       if (isset($koneksi) && $koneksi->connect_errno === 0) {
           $koneksi->rollback();
       }
       $_SESSION['error_key'] = $e->getMessage();
   } finally {
       if (isset($stmt)) {
           $stmt->close();
       }
       if (isset($koneksi)) {
           $koneksi->close();
       }
   }

   header("Location: ../Kunci.php");
   exit;
}
?>