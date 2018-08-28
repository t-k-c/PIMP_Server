import requests
r = requests.post(
		"https://api.deepai.org/api/image-similarity",
files={
        'image1': open('../post_thumbnails/utufb.jpg', 'rb'),
        'image2': open('../site_thumbnails/shortname.jpeg', 'rb'),
    },
    headers={'api-key': 'faeb20ec-30b6-4cff-b7d4-3a72df75656c'}
)
print(r.json())