import sys
import tweepy
import time
from datetime import datetime, timedelta
 
try:
	auth = tweepy.OAuthHandler("hiTfkNbObO2jh8uQ75nvYPkWb", "xBDUo4TeegAFkSQyVGtJJscHGMnVm3XDOKXK8IAbKbmNkafkAA")
	auth.set_access_token("2891783463-55aMHmxGItt1OY6NddAgcGl41BJd7hZbVjGDxK6", "HuiBZBH6KMSE50cz3bKjcupabtZjZLsxq94rvjdgGdCZV")
	 
	api = tweepy.API(auth)

	user = "Aurora_Alerts"
	#user = "Europe1"

	status_user = api.user_timeline(id = user)
	latest_status_id_str = "0"
	#print latest_status_id_str
	kp_level = -10
	status = api.user_timeline(id = user, count = 1)

	while True:	
		time.sleep(0.2)
		#print "avant if status "
		if status:
			if status[0].id_str != latest_status_id_str:
				#print "dans if status"
				latest_status_id_str = status[0].id_str
				tweet_str =  status[0].text
				tweet_date = status[0].created_at
				tweet_date = tweet_date + timedelta(hours=1)
				debut_kp = tweet_str.find("(")
				fin_kp = tweet_str.find("Kp")
				if debut_kp <> -1:
					if fin_kp <> -1:
						kp_level = tweet_str[debut_kp+1:fin_kp-1]
						#print kp_level

				if float(kp_level) >= 7:
					#print str(tweet_date) + " - " + tweet_str[:fin_kp+3]
					sys.argv=["sendmail.py","notifyme87@gmail.com","Alert Aurore Boreale",str(tweet_date)+ " - " + tweet_str[:fin_kp+3]]
					execfile("sendmail.py")

			kp_level = -10
		time.sleep(60)

except KeyboardInterrupt:
	print "Bye"
	sys.exit()