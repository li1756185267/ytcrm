#!/bin/bash

ip='192.168.1.139'
username='root'
passwd='alex333'

dir=`pwd`
yum -y install expect

rm -rf /root/ocaml-3.10.2* /root/unison-2.32.52*

cd /root && wget http://caml.inria.fr/pub/distrib/ocaml-3.10/ocaml-3.10.2.tar.gz && tar -zxvf ocaml-3.10.2.tar.gz && cd ocaml-3.10.2/ && ./configure && make world opt && make install

yum -y install ctags-etags && cd /root && wget http://www.seas.upenn.edu/~bcpierce/unison//download/releases/unison-2.32.52/unison-2.32.52.tar.gz && tar -zxvf unison-2.32.52.tar.gz && cd unison-2.32.52/ && make UISTYLE=text THREADS=true STATIC=true && /usr/bin/cp -f unison /usr/bin/unison

if [ ! -f /root/.ssh/id_rsa.pub ]; then
type=0
$dir/unison-expect.sh $ip $username $passwd $type
fi

type=2
$dir/unison-expect.sh $ip $username $passwd $type

type=1
$dir/unison-expect.sh $ip $username $passwd $type

# unison数据推送
# unison ssh://root@192.168.1.139:22//root/unison-test /root/unison-test -batch