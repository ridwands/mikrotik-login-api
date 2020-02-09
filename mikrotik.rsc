# feb/09/2020 01:47:25 by RouterOS 6.45.8
# software id = 
#
#
#
/interface ethernet
set [ find default-name=ether1 ] disable-running-check=no
set [ find default-name=ether2 ] disable-running-check=no
/interface wireless security-profiles
set [ find default=yes ] supplicant-identity=MikroTik
/ip hotspot profile
add hotspot-address=192.168.1.1 login-by=http-pap name=hsprof1
/ip pool
add name=hs-pool-2 ranges=192.168.1.2-192.168.1.254
/ip dhcp-server
add address-pool=hs-pool-2 disabled=no interface=ether2 lease-time=1h name=\
    dhcp1
/ip hotspot
add address-pool=hs-pool-2 disabled=no interface=ether2 name=hotspot1 \
    profile=hsprof1
/ip address
add address=192.168.1.1/24 interface=ether2 network=192.168.1.0
/ip dhcp-client
add disabled=no interface=ether1
/ip dhcp-server network
add address=192.168.1.0/24 comment="hotspot network" gateway=192.168.1.1
/ip dns
set servers=8.8.8.8
/ip firewall filter
add action=passthrough chain=unused-hs-chain comment=\
    "place hotspot rules here" disabled=yes
/ip firewall nat
add action=passthrough chain=unused-hs-chain comment=\
    "place hotspot rules here" disabled=yes
add action=masquerade chain=srcnat comment="masquerade hotspot network" \
    src-address=192.168.1.0/24
/ip hotspot ip-binding
add address=192.168.1.254 type=bypassed
/ip hotspot user
add name=admin password=admin
/ip hotspot walled-garden
add comment="place hotspot rules here" disabled=yes
/ip hotspot walled-garden ip
add action=accept disabled=no dst-address=192.168.1.254
