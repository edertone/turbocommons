function Start-ChromeApp {
    param (
        [Parameter(Mandatory = $true)]
        [string]$Url,
        [int]$Width = 1024,
        [int]$Height = 768,
        [int]$TimeoutMs = 5000
    )

    if (-not ("Win32.Win32MoveWindow" -as [type])) {
        Add-Type -MemberDefinition '[DllImport("user32.dll")] public static extern bool MoveWindow(IntPtr hWnd, int X, int Y, int nWidth, int nHeight, bool bRepaint);' -Name "Win32MoveWindow" -Namespace "Win32" | Out-Null
    }

    # Snapshot existing chrome window handles BEFORE launching
    $existingHandles = @(
        Get-Process chrome -ErrorAction SilentlyContinue |
        Where-Object { $_.MainWindowHandle -ne 0 } |
        Select-Object -ExpandProperty MainWindowHandle
    )

    Start-Process "chrome.exe" -ArgumentList "--app=$Url" | Out-Null

    # Poll for a NEW window handle that wasn't there before
    $hWnd = 0
    $deadline = (Get-Date).AddMilliseconds($TimeoutMs)
    while ((Get-Date) -lt $deadline) {
        $candidates = Get-Process chrome -ErrorAction SilentlyContinue
        foreach ($p in $candidates) {
            $p.Refresh()
            if ($p.MainWindowHandle -ne 0 -and $existingHandles -notcontains $p.MainWindowHandle) {
                $hWnd = $p.MainWindowHandle
                break
            }
        }
        if ($hWnd -ne 0) { break }
        Start-Sleep -Milliseconds 100
    }

    if ($hWnd -and $hWnd -ne 0) {
        [Win32.Win32MoveWindow]::MoveWindow($hWnd, 100, 100, $Width, $Height, $true) | Out-Null
    }
    else {
        Write-Warning "Could not pinpoint the Chrome window handle to resize it."
    }
}