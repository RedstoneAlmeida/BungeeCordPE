<?php


namespace BungeePM\network\protocol;


class PacketInfo
{

    const PACKET_PING = "ping";
    const PACKET_PONG = "pong";

    const MESSAGE_SEND_PACKET = 65;
    const SERVER_LOGIN_PACKET = 89;
    const SERVER_DISCONNECT_PACKET = 93;
    const SERVER_ADD_REQUEST_PACKET = 37;

    const CLIENT_PROTOCOL = "200";

}