import json, urllib, requests

f = open('data.json')
j = json.load(f)

marker_base = 'http://projects.statesman.com/features/top-restaurants/assets/markers/'

map_base = 'https://maps.googleapis.com/maps/api/staticmap?'
map_params = {}

for i, restaurant in enumerate(j['top']):
  print "Downloading map for " + restaurant['name']

  latlong = str(restaurant['lat']) + ',' + str(restaurant['long'])
  map_params['center'] = latlong
  map_params['zoom'] = '12'
  map_params['size'] = '325x225'
  map_params['maptype'] = 'terrain'
  map_params['markers'] = 'icon:' + marker_base + str(restaurant['position']) + '.png' + '|' + latlong

  r = requests.get(map_base + urllib.urlencode(map_params), stream=True)
  path = '../assets/maps/' + str(i) + '.jpg'
  if r.status_code == 200:
      with open(path, 'wb') as f:
          for chunk in r.iter_content():
              f.write(chunk)
              
