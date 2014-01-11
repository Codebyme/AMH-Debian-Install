#!/usr/bin/perl -w
use strict;
sub main{
        my @lines = `ps -eo user,pid,etime,cmd | grep sshd`;
        my $users;
        for my $line (@lines) {
                if(my ($user, $pid, $etime, $cmd) = $line =~ /^([^\s]+)\s+(\d+)\s+([^\s]+)\s+(sshd:.+)$/) {
                        next if($user eq 'root');
                        my $proc = {'pid', $pid, 'etime', $etime, 'cmd', $cmd};
                        push @{$users->{$user}}, $proc;
                }
        }
        for my $key(keys(%$users)) {
                my @sshs = sort {
                        my ($lb, $la) = (length($b->{'etime'}), length($a->{'etime'}));
                        if($lb == $la) {
                                $b->{'etime'} cmp $a->{'etime'};
                        } else {
                                $lb <=> $la;
                        }
                } @{$users->{$key}};
                for (1 .. 1) { shift @sshs; };
                for my $ssh (@sshs) {
                        kill 9, $ssh->{'pid'};
                }
        }
}

#while(1) {
        main;
#       sleep 3;
#}