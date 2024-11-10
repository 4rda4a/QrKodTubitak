<?php
/**
 * BarcodeQR - Code QR Barcode Image Generator (PNG)
 *
 * @package BarcodeQR
 * @category BarcodeQR
 * @name BarcodeQR
 * @version 1.0
 * @author Shay Anderson
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * This is free software and is distributed WITHOUT ANY WARRANTY
 */
final class BarcodeQR {
    /**
     * Chart API URL
     */
    const API_CHART_URL = "https://api.qrserver.com/v1/create-qr-code/";

    /**
     * Code data
     *
     * @var string $_data
     */
    private $_data;

    /**
     * Set data for a URL
     *
     * @param string $url
     */
    public function url($url) {
        $this->_data = preg_match("#^https?\:\/\/#", $url) ? $url : "http://{$url}";
    }

    /**
     * Generate QR code image
     *
     * @param int $size
     * @param string $filename
     * @return bool
     */
    public function draw($size = 150, $filename = null) {
        $apiUrl = self::API_CHART_URL . "?size={$size}x{$size}&data=" . urlencode($this->_data);
        $img = file_get_contents($apiUrl);

        if ($img !== false) {
            if ($filename) {
                if (!preg_match("#\.png$#i", $filename)) {
                    $filename .= ".png";
                }
                return file_put_contents($filename, $img);
            } else {
                header("Content-type: image/png");
                echo $img;
                return true;
            }
        }
        return false;
    }

    // Diğer yöntemler (contact, email, sms, vs.) ekleyebilirsiniz.
}

?>
