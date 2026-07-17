function Wait-ForUrl-Ready {
    param(
        [Parameter(Mandatory = $true)]
        [string]$Url,

        [int]$MaxAttempts = 60,

        [int]$DelaySeconds = 2,

        [int]$TimeoutSeconds = 3
    )

    # Bypass SSL validation for this session (needed on PS 5.1; harmless on 7+)
    if (-not ("TrustAllCertsPolicy" -as [type])) {
        Add-Type @"
using System.Net;
using System.Security.Cryptography.X509Certificates;
public class TrustAllCertsPolicy : ICertificatePolicy {
    public bool CheckValidationResult(ServicePoint sp, X509Certificate cert, WebRequest req, int problem) {
        return true;
    }
}
"@
        [System.Net.ServicePointManager]::CertificatePolicy = New-Object TrustAllCertsPolicy
    }

    Write-Host "Waiting for $Url to be ready..."
    $attempt = 0
    $ready = $false

    do {
        $attempt++
        try {
            $response = Invoke-WebRequest -Uri $Url -UseBasicParsing -TimeoutSec $TimeoutSeconds
            $ready = $response.StatusCode -eq 200
        }
        catch {
            $ready = $false
        }
        if (-not $ready) {
            Start-Sleep -Seconds $DelaySeconds
        }
    } until ($ready -or $attempt -ge $MaxAttempts)

    if (-not $ready) {
        Write-Warning "$Url did not become ready after $MaxAttempts attempts."
    }

    return $ready
}