<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Load the XML content from the file
$xmlFilePath = realpath(__DIR__ . '/../../artifacts-coverage-report/coverage-report.xml');
if (!$xmlFilePath || !file_exists($xmlFilePath)) {
    echo "Error: XML file not found.";
    exit;
}

$xmlContent = file_get_contents($xmlFilePath);
$xml = simplexml_load_string($xmlContent);

if (!$xml) {
    echo "Failed to load XML.";
    exit;
}

// Get current date and time for report generation
date_default_timezone_set('Asia/Kolkata');
$reportGeneratedAt = date("F j, Y, g:i A");

// Start HTML structure
$html = '<!DOCTYPE html><html lang="en"><head>';
$html .= '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
$html .= '<meta http-equiv="X-UA-Compatible" content="ie=edge"><title>Coverage Report</title>';
$html .= '<style>';
$html .= 'body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }';
$html .= '.container { width: 90%; max-width: 1200px; margin: 0 auto; padding: 20px; background-color: white; }';
$html .= 'h1 { color: #333; text-align: center; }';
$html .= '.metrics-table, .lines-table { width: 100%; border-collapse: collapse; margin: 20px 0; }';
$html .= '.metrics-table th, .lines-table th, .metrics-table td, .lines-table td { padding: 8px; text-align: left; border: 1px solid #ddd; }';
$html .= '.metrics-table th, .lines-table th { background-color: #f2f2f2; }';
$html .= 'h2 { color: #444; margin-bottom: 10px; }';
$html .= '.package-section, .file-section { margin-bottom: 40px; }';
$html .= '.line-info { margin-left: 20px; color: #555; }';
$html .= '.summary { font-weight: bold; margin-bottom: 20px; }';
$html .= '.toggle-button { background-color: #4CAF50; color: white; padding: 5px 10px; cursor: pointer; border: none; text-align: center; margin-left: 10px; }';
$html .= '.toggle-button:hover { background-color: #45a049; }';
$html .= '.lines-details { display: none; }';
$html .= '</style></head><body>';

$html .= '<div class="container">'; // Start of container
$html .= '<h1>Coverage Report</h1>';
$html .= '<p><strong>Report Generated At:</strong> ' . $reportGeneratedAt . '</p>';  // Display date and time

// Separate the files into packages and standalone files
$packages = [];
$standaloneFiles = [];

// Process files under the package
if (isset($xml->project->package)) {
    foreach ($xml->project->package as $package) {
        $packageName = (string) $package['name'];
        $filesInPackage = [];

        foreach ($package->file as $file) {
            $filesInPackage[] = $file;
        }

        $packages[$packageName] = $filesInPackage;
    }
}

// Process files outside of any package
if (isset($xml->project->file)) {
    foreach ($xml->project->file as $file) {
        $standaloneFiles[] = $file;
    }
}

// Project Summary - Count files dynamically
$totalFiles = 0;
$totalLines = 0;
$totalCoverableLines = 0;
$totalCoveredLines = 0;
$totalUncoveredLines = 0;
$totalComplexity = 0;  // New variable for total complexity

// Calculate total complexity and metrics for packages
foreach ($packages as $packageName => $files) {
    foreach ($files as $file) {
        $totalFiles++;
        $totalLines += (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0);
        $totalCoverableLines += (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0);
        $totalCoveredLines += (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0);
        $totalUncoveredLines += (isset($file->metrics['uncoveredstatements']) ? (int) $file->metrics['uncoveredstatements'] : 0);
        
        // Calculate total complexity for each file
        foreach ($file->line as $line) {
            if (isset($line['complexity'])) {
                $totalComplexity += (int) $line['complexity'];
            }
        }
    }
}

// Calculate metrics for standalone files
foreach ($standaloneFiles as $file) {
    $totalFiles++;
    $totalLines += (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0);
    $totalCoverableLines += (isset($file->metrics['coverable']) ? (int) $file->metrics['coverable'] : 0);
    $totalCoveredLines += (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0);
    $totalUncoveredLines += (isset($file->metrics['uncoveredstatements']) ? (int) $file->metrics['uncoveredstatements'] : 0);

    // Calculate total complexity for each standalone file
    foreach ($file->line as $line) {
        if (isset($line['complexity'])) {
            $totalComplexity += (int) $line['complexity'];
        }
    }
}

// Calculate overall coverage percentage
$overallCoverage = 0;
if ($totalLines > 0) {
    $overallCoverage = ($totalCoveredLines / $totalLines) * 100;
}

// Project Summary
$html .= '<div class="summary">';
$html .= '<h2>Project Summary</h2>';
$html .= '<table class="metrics-table">';
$html .= '<tr><th>Attribute</th><th>Value</th></tr>';
$html .= '<tr><td>Generated</td><td>' . $reportGeneratedAt . '</td></tr>';
$html .= '<tr><td>Files</td><td>' . $totalFiles . '</td></tr>';
$html .= '<tr><td>Lines of Code (LOC)</td><td>' . $totalLines . '</td></tr>';
$html .= '<tr><td>Non-Comment Lines of Code (NLOC)</td><td>' . $totalCoverableLines . '</td></tr>';
$html .= '<tr><td>Covered Lines</td><td>' . $totalCoveredLines . '</td></tr>';
$html .= '<tr><td>Uncovered Lines</td><td>' . $totalUncoveredLines . '</td></tr>';
$html .= '<tr><td>Overall Coverage</td><td>' . number_format($overallCoverage, 2) . '%</td></tr>';
$html .= '<tr><td>Total Complexity</td><td>' . $totalComplexity . '</td></tr>';  // Display total complexity
$html .= '</table>';
$html .= '</div>';

// Display Packages and their Files
foreach ($packages as $packageName => $files) {
    $html .= '<div class="package-section">';
    $html .= '<h2>Package: ' . $packageName . '</h2>';
    
    // Display files in the package
    $html .= '<table class="metrics-table">';
    $html .= '<tr><th>File Name</th><th>Lines of Code (LOC)</th><th>Covered Statements</th><th>Uncovered Statements</th><th>Complexity</th><th>Methods</th><th>Coverage (%)</th><th>Line-Level Info</th></tr>';

    foreach ($files as $file) {
        // Extract just the file name using basename()
        $fileName = basename((string) $file['name']);  // Extract file name from the full path
        $loc = (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0);
        $covered = (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0);
        $uncovered = (isset($file->metrics['uncoveredstatements']) ? (int) $file->metrics['uncoveredstatements'] : 0);
        $complexity = 0;
        $methods = (isset($file->metrics['methods']) ? (int) $file->metrics['methods'] : 0);
        $coveragePercentage = $loc > 0 ? ($covered / $loc) * 100 : 0;

        foreach ($file->line as $line) {
            if (isset($line['complexity'])) {
                $complexity += (int) $line['complexity'];
            }
        }

        $lineDetailsId = md5($fileName);
        $html .= '<tr>';
        $html .= '<td>' . $fileName . '</td>';  // Display only the file name
        $html .= '<td>' . $loc . '</td>';
        $html .= '<td>' . $covered . '</td>';
        $html .= '<td>' . $uncovered . '</td>';
        $html .= '<td>' . $complexity . '</td>';
        $html .= '<td>' . $methods . '</td>';
        $html .= '<td>' . number_format($coveragePercentage, 2) . '%</td>';
        $html .= '<td><button class="toggle-button" onclick="toggleDetails(\'' . $lineDetailsId . '\')">Show Line-Level Info</button></td>';
        $html .= '</tr>';

        $html .= '<tr><td colspan="8">';
        $html .= '<div id="' . $lineDetailsId . '" class="lines-details">';
        $html .= '<table class="lines-table">';
        $html .= '<tr><th>Line Number</th><th>Type</th><th>Name</th><th>Visibility</th><th>Complexity</th><th>Count</th></tr>';

        foreach ($file->line as $line) {
            if ($line['type'] == 'method') {
                $html .= '<tr>';
                $html .= '<td>' . (int) $line['num'] . '</td>';
                $html .= '<td>' . (string) $line['type'] . '</td>';
                $html .= '<td>' . (isset($line['name']) ? (string) $line['name'] : '-') . '</td>';
                $html .= '<td>' . (isset($line['visibility']) ? (string) $line['visibility'] : '-') . '</td>';
                $html .= '<td>' . (isset($line['complexity']) ? (int) $line['complexity'] : 0) . '</td>';
                $html .= '<td>' . (int) $line['count'] . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table>';
        $html .= '</div>';  // End line-level details
        $html .= '</td></tr>';
    }

    $html .= '</table>';
    $html .= '</div>';
}

// Display Standalone Files
if (count($standaloneFiles) > 0) {
    $html .= '<div class="package-section">';
    $html .= '<h2>Standalone Files</h2>';
    
    // Display standalone files
    $html .= '<table class="metrics-table">';
    $html .= '<tr><th>File Name</th><th>Lines of Code (LOC)</th><th>Covered Statements</th><th>Uncovered Statements</th><th>Complexity</th><th>Methods</th><th>Coverage (%)</th><th>Line-Level Info</th></tr>';

    foreach ($standaloneFiles as $file) {
        $fileName = basename((string) $file['name']);
        $loc = (isset($file->metrics['loc']) ? (int) $file->metrics['loc'] : 0);
        $covered = (isset($file->metrics['coveredstatements']) ? (int) $file->metrics['coveredstatements'] : 0);
        $uncovered = (isset($file->metrics['uncoveredstatements']) ? (int) $file->metrics['uncoveredstatements'] : 0);
        $complexity = 0;
        $methods = (isset($file->metrics['methods']) ? (int) $file->metrics['methods'] : 0);
        $coveragePercentage = $loc > 0 ? ($covered / $loc) * 100 : 0;

        foreach ($file->line as $line) {
            if (isset($line['complexity'])) {
                $complexity += (int) $line['complexity'];
            }
        }

        $lineDetailsId = md5($fileName);
        $html .= '<tr>';
        $html .= '<td>' . $fileName . '</td>';  // Display only the file name
        $html .= '<td>' . $loc . '</td>';
        $html .= '<td>' . $covered . '</td>';
        $html .= '<td>' . $uncovered . '</td>';
        $html .= '<td>' . $complexity . '</td>';
        $html .= '<td>' . $methods . '</td>';
        $html .= '<td>' . number_format($coveragePercentage, 2) . '%</td>';
        $html .= '<td><button class="toggle-button" onclick="toggleDetails(\'' . $lineDetailsId . '\')">Show Line-Level Info</button></td>';
        $html .= '</tr>';

        $html .= '<tr><td colspan="8">';
        $html .= '<div id="' . $lineDetailsId . '" class="lines-details">';
        $html .= '<table class="lines-table">';
        $html .= '<tr><th>Line Number</th><th>Type</th><th>Name</th><th>Visibility</th><th>Complexity</th><th>Count</th></tr>';

        foreach ($file->line as $line) {
            if ($line['type'] == 'method') {
                $html .= '<tr>';
                $html .= '<td>' . (int) $line['num'] . '</td>';
                $html .= '<td>' . (string) $line['type'] . '</td>';
                $html .= '<td>' . (isset($line['name']) ? (string) $line['name'] : '-') . '</td>';
                $html .= '<td>' . (isset($line['visibility']) ? (string) $line['visibility'] : '-') . '</td>';
                $html .= '<td>' . (isset($line['complexity']) ? (int) $line['complexity'] : 0) . '</td>';
                $html .= '<td>' . (int) $line['count'] . '</td>';
                $html .= '</tr>';
            }
        }

        $html .= '</table>';
        $html .= '</div>';  // End line-level details
        $html .= '</td></tr>';
    }

    $html .= '</table>';
    $html .= '</div>';
}

$html .= '<script>';
$html .= 'function toggleDetails(id) {';
$html .= '    var details = document.getElementById(id);';
$html .= '    if (details.style.display === "none") {';
$html .= '         details.style.display = "block";';
$html .= '    } else {';
$html .= '        details.style.display = "none";';
$html .= '    }';
$html .= '}</script>';

$html .= '</div>'; // End of container
$html .= '</body></html>';

// Output the HTML content
// echo $html;

// Save the HTML report as a file and trigger download
$reportFilePath = __DIR__ . '/../../artifacts-coverage-report/coverage-report.html';

if (file_exists($reportFilePath)) {
    unlink($reportFilePath); 
}

file_put_contents($reportFilePath, $html);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($reportFilePath) . '"');
header('Content-Length: ' . filesize($reportFilePath));
readfile($reportFilePath);

exit;
?>
