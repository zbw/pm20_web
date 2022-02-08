#!/bin/env perl
# nbt, 28.5.2018

# Create a PM20 folder PDF from METS und JPEGs

# to be called with the PDF url, redirects to that one when finished

use strict;
use warnings;

use CGI;
use CGI::Push qw(:standard);
use Data::Dumper;
use File::Temp;
use Path::Tiny;
use Readonly;

Readonly my $PDF_URL_ROOT => 'https://pm20.zbw.eu/pdf/';

# init cgi object
my $q = CGI->new;

# cleanup cache by apache user
##`/bin/rm -rf /pm20/cache/pdf/*`;
##`/bin/rm -rf /tmp/.folder2pdfLGGHMXQS`;
##exit;

# init log file
##$File::Temp::KEEP_ALL = 1;
my $log_file = File::Temp->new(
  TEMPLATE => '/tmp/.folder2pdf_XXXXXXXX',
  SUFFIX   => '.log',
  PERMS    => 0666
);
## init log here to avoid race condition
path($log_file)->touch;

# read param
my $pdf_url = $q->param('pdf');

# validate $pdf_url
if ( not $pdf_url ) {
  print $q->header( 'text/plain', '400 Bad Request' );
  print "Missing PDF URL\n";
  exit;
}

if (
  not $pdf_url =~
m;^$PDF_URL_ROOT(((co|pe)/\d{4}xx/\d{6}/\d{6})|((sh|wa)/\d{4}xx/\d{6}/\d{4}xx/\d{6}/[0-9,]{13}))\.pdf$;
  )
{
  print $q->header( 'text/plain', '400 Bad Request' );
  print "Mal-formed PDF URL: $pdf_url\n";
  exit;
}

# fork this process
my $pid = fork();
die "Fork failed: $!" if !defined $pid;

if ( $pid == 0 ) {

  # do this in the child
  open STDIN,  "</dev/null";
  open STDOUT, ">/dev/null";
  open STDERR, ">/dev/null";
  system("/usr/bin/perl /pm20/bin/folder2pdf.pl $pdf_url $log_file \&");
  exit;
}

##my $wait_return_value = wait;
##if ($wait_return_value != $pid) {
##  die "Der Child-Prozess wurde nicht ordnungsgemäß beendet!\n $?";
##}

## CGI::Push does not work with many browsers - see
## https://stackoverflow.com/questions/11532735/how-to-create-a-countdown-cgi-script-in-perl
do_push(
  -next_page => \&next_page,
  -last_page => \&last_page,
  -delay     => 1,
  -nph       => 0,
  -type      => 'dynamic',
);

sub next_page {
  my $message = `cat $log_file`;

  if ( $message =~ m/Done/msx ) {
    return undef;
  }
  return "Content-type: text/plain\n\n", $message;
}

sub last_page {

  # redirect to the newly created file
  return $q->header(
    -refresh => "1; URL=$pdf_url",
    -type    => 'text/html'
    ),
    'Done. Please reload, if PDF is not downloaded automatically.';
}

####################

sub url_ok {
  my $pdf_url = shift or die "param missing\n";

  if ( $pdf_url =~ m/^${PDF_URL_ROOT}[a-z0-9\/\.]+$/ ) {
    return 1;
  } else {
    return 0;
  }
}

