function Start-ChromeApp {
    param (
        [Parameter(Mandatory = $true)]
        [string]$Url,
        [int]$Width = 1024,
        [int]$Height = 768
    )

    # Load Windows API for resizing windows if not already loaded
    if (-not ("Win32.Win32MoveWindow" -as [type])) {
        Add-Type -MemberDefinition '[DllImport("user32.dll")] public static extern bool MoveWindow(IntPtr hWnd, int X, int Y, int nWidth, int nHeight, bool bRepaint);' -Name "Win32MoveWindow" -Namespace "Win32" | Out-Null
    }

    # Launch Chrome app
    $proc = Start-Process "chrome.exe" -ArgumentList "--app=$Url" -PassThru
    Start-Sleep -Milliseconds 800  # Give Chrome a moment to render the window

    # Fallback: If Chrome was already running, $proc.MainWindowHandle might be 0.
    # We look for the most recently created Chrome window handle instead.
    $hWnd = $proc.MainWindowHandle
    if ($hWnd -eq 0) {
        $hWnd = (Get-Process chrome | Where-Object { $_.MainWindowHandle -ne 0 } | Sort-Object StartTime -Descending | Select-Object -First 1).MainWindowHandle
    }

    # Resize if a valid window handle was found
    if ($hWnd -and $hWnd -ne 0) {
        [Win32.Win32MoveWindow]::MoveWindow($hWnd, 100, 100, $Width, $Height, $true) | Out-Null
    }
    else {
        Write-Warning "Could not pinpoint the Chrome window handle to resize it."
    }
}