import json, urllib, requests

f = open('data.json')
j = json.load(f)

marker_base = 'http://projects.statesman.com/features/top-restaurants/assets/markers/'

map_base = 'https://maps.googleapis.com/maps/api/staticmap?'
map_file_dest = '../assets/maps/'
map_params_default = {
  'zoom': '12',
  'size': '300x250',
  'maptype': 'terrain'
}

def save_map(url, file_name):
  """
  """
  r = requests.get(url, stream=True)
  path = map_file_dest + str(file_name) + '.jpg'
  if r.status_code == 200:
      with open(path, 'wb') as f:
          for chunk in r.iter_content():
              f.write(chunk)

def marker_string(position, latlong):
  """
  """
  return 'icon:' + marker_base + str(position) + '.png' + '|' + latlong

# Loop through the full set of top restaurants
for i, restaurant in enumerate(j['top']):
  print "Downloading map for " + restaurant['name']

  latlong = str(restaurant['lat']) + ',' + str(restaurant['long'])

  map_params = map_params_default
  map_params['center'] = latlong
  map_params['markers'] = marker_string(restaurant['position'], latlong)

  save_map(map_base + urllib.urlencode(map_params), i)

# Then save a static map for the combined Uchi/Uchiko listing
print "Downloading a special map for Uchis."

map_params = map_params_default

uchi = j['top'][2]
uchiko = j['top'][3]

latlong_uchi = str(uchi['lat']) + ',' + str(uchi['long'])
latlong_uchiko = str(uchiko['lat']) + ',' + str(uchiko['long'])

map_params['center'] = str((uchi['lat'] + uchiko['lat']) / 2) + 'x' + str((uchi['long'] + uchiko['long']) / 2)
map_params['size'] = '300x325'
map_params['markers'] = marker_string(3, latlong_uchi)
save_map(map_base + urllib.urlencode(map_params) + '&markers=' + urllib.quote_plus(marker_string(3, latlong_uchiko)), 'uchis')
