#AMH-Debian-Install
==================
####Q:What is it?
#####A:This is a Project to Make *.deb For AMH.And We Provided a Thirdparty AMH Module Download Platform For Thirdparty developers.
####Q:How I use *.deb
#####A:We will provided a bash script, Then, you can download this script by **wget** and run it by **sh**.
####Q:How I Use Third Party Module?
#####A:You can download the script we provide to you and replace the official script on /root/amh
####Q:How I Upload A new Module?
#####A:you can Send your module to me at **codebyme@mail.com**
####Q: How I download A Thirdparty module.
#####A:You Only Can use `amh module download modulename` to download a ThirdParty Module.
===============
##Code
Download Script And Replace It:
`cd /root/amh && mv module module.bak && wget -c --no-check-certificate https://github.com/Codebyme/AMH-Debian-Install/blob/master/module && chmod +x /root/amh/module`

Replaced To Official:
` rm -f /root/amh/module && mv /root/amh/module.bak /root/amh/module && chmod +x /root/amh/module`