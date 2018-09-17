#!/usr/bin/expect

set ip [lindex $argv 0]
set username [lindex $argv 1]
set passwd [lindex $argv 2]
set type [lindex $argv 3]

if {$type==0} {
    spawn ssh-keygen -t rsa -P ""
    expect {
        "yes/no" {send "\r";exp_continue}
        "Enter" {send "\r";exp_continue}
        "Overwrite" {send "\r";exp_continue}
    }
} elseif {$type==1} {
    spawn ssh $username@$ip "dir /root/.ssh || mkdir /root/.ssh && ls /root/.ssh/authorized_keys || touch /root/.ssh/authorized_keys && cat /root/.ssh/unison_rsa.pub >> /root/.ssh/authorized_keys"
    expect {
        "yes/no" {send "yes\r"; exp_continue}
        "password" {send "$passwd\r";}
    }
    interact
} elseif {$type==2} {
    spawn scp /root/.ssh/id_rsa.pub 192.168.1.139:/root/.ssh/unison_rsa.pub
    expect {
        "yes/no" {send "yes\r"; exp_continue}
        "password" {send "$passwd\r";}
    }
    interact
}
