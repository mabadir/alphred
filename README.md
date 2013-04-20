# Alphred for Alfred 2 Workflow

Alphred is a Distraction-free Alfred 2 posting Workflow, so you can just post your thoughts to [App.net] without launching any apps, and while running any other application. Simply type _adn_ followed by your post.

## How to Use Alphred

### Installation

- Download Alphred.
- Open the workflow file, to install it directly to Alfred 2 (*Powerpack license is required for Workflows*).

### Authentication

- Alphred needs to be authenticated the first time only, or if the token is revoked from the App.net settings.
- To authenitcate Alphred, run the following command into Alfred 2:
	`adnauth user:<username> pass:<password>`
- Where `<username>` is App.net username and `<password>` is the equivalent password.
- App.net had introduced the *Two Factor Authentication* for increased security. If you had this enabled for your profile, then you need to use an *App-specific* password for `<password>`.
- Alphred should send a notification to confirm that it is now authenticated and ready.
- All set! Let's have fun.

### Usage

#### Posting

- To post to App.net, all you have to do is to launch Alfred and then type:
	`adn <post>`
- `<post>` represents what you need to say to App.net. Simple isn't it.

#### Private Message

- To send PM to different recepients you have to launch Alfred, then type:
	`adnpm @username1 @username2 <PM can include other @username3>`
- This will send a PM to `username1` and `username2` the PM `PM can include other @username3`. No PM or notification will be sent to `username3`.

#### Following

- In order to follow Mass users, you need to launch Alfred, then type:
	`adnf @username1 @username2`
- This will follow both users `username1` and `username2`.
- A notification will be displayed with how many users are followed and the number of issues faced while following the others.

#### Sharing Browsers active page

- This feature allows you share your Google Chrome or Safari active page on App.net directly.
- Simply launch Alfred, then type:
	`adnshare <comment>`
- Alfred will show you two choices to share either:
	1. Google Chrome Active tab
	2. Safari Active tab
- Only the running browsers will be displayed.
- Select the required choice, this will post to App.net:
	`<comment> [*Windows Title*](URL)`

**General Options**
- You can run `adnauth` anytime, to get a new token.
- You can always use Markdown style links, in order to add entities links to you post.

---

# Changelog

*Alphred 2.0*
- Added Safari support for Sharing Browser page feature `adnshare`.
- Added Commenting to Sharing Browser page feature.
- Fixed some issues with posts contain `double quotes` and `single quotes`.
- Removed Cmd+Enter to trigger Alphred.

*Alphred 2b1*
- Added Markdown links support for posting with entities links.
- Added Mass PM support.
- Added Mass Follow support.
- Added Sharing Browser page support.
- Added Cmd+Enter to trigger Alphred instead of Enter.

*Alphred 1.0*
- Initial release.
- Added distraction-free posting to App.net through Alfred 2 workflows.
