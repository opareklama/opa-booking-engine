<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Providers;

use OpaReklama\Booking\Interfaces\PdfGeneratorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class DomPdfProvider implements PdfGeneratorInterface {
    public function generate(string $html, string $output_path): bool {
        try {
            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');
            $options->set('isRemoteEnabled', true); // For external images if any
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            $output = $dompdf->output();
            if ($output === null) return false;
            
            // Ensure directory exists
            $dir = dirname($output_path);
            if (!is_dir($dir)) {
                wp_mkdir_p($dir);
            }
            
            file_put_contents($output_path, $output);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
