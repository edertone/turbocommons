function Start-DockerDesktop-IfNeeded {
    <#
    .SYNOPSIS
        Makes sure Docker Desktop is running, starting it if needed and waiting until the daemon responds.
    .PARAMETER DockerDesktopPath
        Path to Docker Desktop.exe. Defaults to the standard install location.
    .PARAMETER PollSeconds
        How many seconds to wait between checks while Docker starts up. Default 5.
    #>
    param(
        [string]$DockerDesktopPath = "C:\Program Files\Docker\Docker\Docker Desktop.exe",
        [int]$PollSeconds = 5
    )

    Write-Host "Checking if Docker is already running..."
    docker info > $null 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Docker is already running."
        return
    }

    if (-not (Test-Path $DockerDesktopPath)) {
        throw "Docker Desktop executable not found at '$DockerDesktopPath'. Pass -DockerDesktopPath to override."
    }

    Write-Host "Docker is not running. Starting Docker Desktop..."
    Start-Process $DockerDesktopPath

    Write-Host "Waiting for Docker to start..."
    do {
        Start-Sleep -Seconds $PollSeconds
        docker info > $null 2>&1
    } while ($LASTEXITCODE -ne 0)

    Write-Host "Docker is now running."
}
