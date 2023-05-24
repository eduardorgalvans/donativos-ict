/////////////////////////////////////////////////////////////////////////////////////////////////////
// Web Notifications HTML API Wrapper
// by Jose M. Alarcon - www.campusmvp.com - 2014
// Open Source - Do not remove this
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Permission values
var WEBNOTIF_PERMISSION_DEFAULT = 0;
var WEBNOTIF_PERMISSION_DENIED = 1;
var WEBNOTIF_PERMISSION_GRANTED = 2;

//Returns current browser's notification object. Base of all notifications. It may change, so let's encapsulate it here.
function getNotificationSingleton() {
	return window.Notification;
}

//Returns true if the web notifications API is supported
function getWebNotificationsSupported() {
	return (!!getNotificationSingleton());
}

//Returns a constant depending on current notification status
//It's better than having to compare a string that may change in the future
function getWebNotificationPermissionStatus() {
	return translateWebNotificationPermissionStatus(getNotificationSingleton().permission);
}

//Trnaslate one of the defined strings for notification permission status into one of our constants
function translateWebNotificationPermissionStatus(status) {
	switch (status) {
		case "granted":
			return WEBNOTIF_PERMISSION_GRANTED;
		case "denied":
			return WEBNOTIF_PERMISSION_DENIED;
		default:
			return WEBNOTIF_PERMISSION_DEFAULT;
	}
}


//Ask the user for permission to show notifications if needed
//It will only work if it's called by an user's action (eg: it will not work in the onload event)
function askForWebNotificationPermissions()
{
	if (getWebNotificationsSupported()) {
		var notif = getNotificationSingleton();
		notif.requestPermission();
	}
}

//Creates a new notification
//Only title and msg are mandatory
//icon: the icon to show along with the notification
//tag: an unique tag to prevent showing messages about the sime issue more than once
//timeout: auto-close the notification
function createNewWebNotification(title, msg, icon, tag, timeout) {
	var options = { body: msg };
	if (icon)
		options.icon = icon;
	if (tag)
		option.tag = tag;

	//Show the notification
	var notif = new Notification(title, options);

	//Autohide notification if specified
	if (timeout && typeof (timeout) == "number")
		setTimeout(closeNotification, timeout, notif);

	return notif;
}

//Shows a web notification with the specified parameters and returns a reference to the current notification
//Only title and message are mandatory parameters.
//If permission is not granted it asks for permission from the user, but returns null for the notification even if its shown. This is because of the asynchronous nature of asking for permission
function showWebNotification(title, msg, icon, tag, timeout){
  if (getWebNotificationsSupported())
  {
	switch (getWebNotificationPermissionStatus()) {
		case WEBNOTIF_PERMISSION_GRANTED:	//Simply show the notification
			return createNewWebNotification(title, msg, icon, tag, timeout);
			break;
		case WEBNOTIF_PERMISSION_DENIED, WEBNOTIF_PERMISSION_DEFAULT:
			//if we don't have permission, then ask for it and later show the notification if granted
			var notifS = getNotificationSingleton();
			notifS.requestPermission( function(status) {
				if (translateWebNotificationPermissionStatus(status) == WEBNOTIF_PERMISSION_GRANTED)
				 	newNotif = createNewWebNotification(title, msg, icon, tag, timeout);
			});
			return null;
			break;
  	}
  }
  return null;	//If it's not supported
}

//Closes a notification
function closeNotification(notif) {
	notif.close();
}


