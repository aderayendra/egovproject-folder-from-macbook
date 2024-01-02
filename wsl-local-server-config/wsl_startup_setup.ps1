echo "`nStarting-Up and Confirguring WSL2 Web Server, Please Do not disturb this process!";
$hostIp = Invoke-Expression "(Get-NetIPAddress -AddressFamily IPv4 -InterfaceAlias Ethernet).IPAddress";
echo "-----------------------`nStarting Apache2 Server";
$output = Invoke-Expression "wsl sudo service apache2 start 2>&1";
echo "Done`nStarting MySQL Server";
$output = Invoke-Expression "wsl sudo service mysql start 2>&1";
# echo "Done`nStarting FTP Server";
# $output = Invoke-Expression "wsl sudo service vsftpd start 2>&1";
echo "Done`nStarting Cron Scheduler";
$output = Invoke-Expression "wsl sudo service cron start 2>&1";
echo "Done`n";
$remoteaddress = bash.exe -c "ifconfig eth0 | grep 'inet '"
$found = $remoteaddress -match '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
if( $found ){
  $remoteaddress = $matches[0];
} else{
  echo "The Script Exited, the ip address of WSL 2 cannot be found";
  exit;
}
#20,21,22,990,10000-20000
$ports=@(80,443);
$addr='0.0.0.0';
$ports_a = $ports -join ",";
echo "-----------------------`nRemoving Old FireWall Rule";
$output = Invoke-Expression "Remove-NetFireWallRule -DisplayName 'WSL2 Firewall Unlock' 2>&1";
echo "Done`nAdding New FireWall Rule";
$output = Invoke-Expression "New-NetFireWallRule -DisplayName 'WSL2 Firewall Unlock' -Direction Outbound -LocalPort $ports_a -Action Allow -Protocol TCP 2>&1";
$output = Invoke-Expression "New-NetFireWallRule -DisplayName 'WSL2 Firewall Unlock' -Direction Inbound -LocalPort $ports_a -Action Allow -Protocol TCP 2>&1";
echo "Done`n";
echo "-----------------------`nCreating PortProxy Rules...`n";
for( $i = 0; $i -lt $ports.length; $i++ ){
  $port = $ports[$i];
  $output = Invoke-Expression "netsh interface portproxy delete v4tov4 listenport=$port listenaddress=$addr 2>&1";
  $output = Invoke-Expression "netsh interface portproxy add v4tov4 listenport=$port listenaddress=$addr connectport=$port connectaddress=$remoteaddress 2>&1";
  echo "${hostIp}:${port} is forwarded to ${remoteaddress}:${port}";
}
echo "`nAll Done... enjoy... author Ade Rayendra`n";
$output = Invoke-Expression "Start-Sleep -s 3";