$ErrorActionPreference = 'Stop'

function Test-HashManifest {
    param(
        [Parameter(Mandatory)] [string] $Directory,
        [Parameter(Mandatory)] [string] $Manifest
    )

    $base = (Resolve-Path -LiteralPath $Directory).Path
    foreach ($line in Get-Content -LiteralPath $Manifest) {
        if ([string]::IsNullOrWhiteSpace($line)) { continue }
        $parts = $line -split '\s{2,}', 2
        if ($parts.Count -ne 2) { throw "Invalid manifest line: $line" }
        $expected = $parts[0].ToUpperInvariant()
        $relative = $parts[1].Replace('/', [IO.Path]::DirectorySeparatorChar)
        $path = Join-Path $base $relative
        if (-not (Test-Path -LiteralPath $path)) { throw "Missing file: $relative" }
        $actual = (Get-FileHash -LiteralPath $path -Algorithm SHA256).Hash
        if ($actual -ne $expected) { throw "Hash mismatch: $relative" }
    }
}

$repo = Split-Path -Parent $PSScriptRoot
Test-HashManifest -Directory "$repo\recovery\sanitized-site\packages" -Manifest "$repo\recovery\sanitized-site\SHA256SUMS.txt"
Test-HashManifest -Directory "$repo\media\originals" -Manifest "$repo\media\originals\SHA256SUMS.txt"
Write-Host 'Recovery packages and original media hashes are valid.'
