#!/usr/bin/env python
import boto3
import os, sys, json
import requests
from pprint import pprint

if __name__ == "__main__":
    azurePriceJSONUrl = "https://azure.microsoft.com/api/v1/pricing/virtual-machines/calculator/?culture=en-us"
    header = {'x-requested-with': 'XMLHttpRequest'}
    priceResponse = requests.get(azurePriceJSONUrl, headers = header)
    azurePriceData = priceResponse.json()
    
    count = 0
    dictVM = {}
    for items in azurePriceData['offers']:
        count = count + 1
        operating_system = items.split('-')[0]
        price_value = 0
        for abc in azurePriceData['offers'][items]['prices']:
            if price_value < azurePriceData['offers'][items]['prices'][abc]:
                price_value = azurePriceData['offers'][items]['prices'][abc]
            else:
                price_value = price_value
        dictVM[items] = {'price': price_value, 'os': operating_system, 'cores': azurePriceData['offers'][items]['cores'], 'ram':azurePriceData['offers'][items]['ram']}
    
     for items in dictVM:
         print items