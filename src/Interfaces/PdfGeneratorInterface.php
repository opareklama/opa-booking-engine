<?php
declare(strict_types=1);

namespace OpaReklama\Booking\Interfaces;

interface PdfGeneratorInterface {
    public function generate(string $html, string $output_path): bool;
}
