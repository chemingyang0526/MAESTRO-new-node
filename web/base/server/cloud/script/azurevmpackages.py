#!/usr/bin/env python

import os, sys, json
import commands

if __name__ == "__main__":
    vmPackages = []
    try:
        login_status, login_output = commands.getstatusoutput("az login --service-principal -u dee048ad-a200-41cf-9b4a-ce563aa06ecd -p Rv8LBsPbzPivFt5sw2fMW+77wIoD9hAlBfFS7AR4BYg= -t dfd034ad-c274-41ae-b40c-88199e6b7528")
        exec_status, exec_output = commands.getstatusoutput("az vm list-sizes --location eastus --output json")
        logout_status, logout_output = commands.getstatusoutput("az logout")
    except Exception as e:
        vmPackages.append( str(e) )
    if(vmPackages):
        print json.dumps(vmPackages, sort_keys=True, separators=(',', ': '))
    else:
        vmPackages.append(exec_output)
        print json.dumps(exec_output, sort_keys=True, separators=(',', ': '))
    sys.exit(0)