from gspread import Client
import simplejson as json
from ConfigParser import ConfigParser


# Get config from file
cfg = ConfigParser()
cfg.readfp(open('creds.cfg'))


# Get config info
username = cfg.get('google', 'username')
password = cfg.get('google', 'password')

# Login to Google
c = Client(auth=(username, password))
c.login()

# Get our election results spreadsheet
s = c.open("Top Restaurants")

# Loop through all worksheets in the spreadsheet, parse them
# and add them to a results list
results = {'picks': {}}
for sheet in s.worksheets():
  if sheet.title == "Top 25":
    results['top'] = sheet.get_all_records()
  else:
    results['picks'][sheet.title.lower().replace(" ", "")] = sheet.get_all_records()

# Write it all out to JSON
json_result = json.dumps(results)
json_out = open('data.json', 'w')
json_out.write(json_result)
json_out.close()

print 'JSON results saved to data.json'
