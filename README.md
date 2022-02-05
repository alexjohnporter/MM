# Alex Porter - Muzmatch API


## Setup
- Run `make docker-run`
- Run `make setup`
- Import postman config and view Env set up in screenshots
- Navigate to `http://localhost:8080` and you should see `Hello muzmatch!`

# Development notes

The "loggedInUser" id is `753080bf-2213-4e2f-bb28-5ba8bba1100c`. This has been set up in fixtures and will always have this ID.

There are no users created on set up, so navigate to `/user/create` to create as many users as you want.

To log in - the email is `foo@bar.com` and the password is `foobar`, this will return your token which you'll need to add as a header (`X-AUTH-TOKEN`)- this is already set up in the Postman config. Logins last 10 minutes and then will need to be refreshed.

The postman config contains all of the query strings for filtering and sorting `profiles`, these are the defaults `?minAge=18&maxAge=99&gender=X&distance=ASC`.

`attractiveness` is calculated by counting the amount of yes swipes a user has had, and the API is set up to always return the most attractive profiles first.

Authentication is set up quite simply for this exercise, I've used standard PHP functionality to hash then verify the passwords and if a password is verified, I am generating a token which is saved against the user record with an expiry.
In a production environment, I'd look at using something like a JWT or oAuth2.

I've gone between using Doctrine & plain SQL - normally, I'd try to be a bit more consistent with my tooling but this was just to showcase I'm comfortable with both frameworks and plain SQL. In some areas, it was just quicker to use certain doctrine methods rather than reinventing the wheel, however using plain SQL was also easier for the distance calculation.

I've used the Command Handler pattern (or Message Handler if you are Symfony) in the controller to deal with data manipulation as I like to keep my controllers as thin as possible.

There are areas of this code that are probably over-engineered for a simple API by using Factories but this was to highlight the way I'd normally develop, especially if more logic is required. Testing the factory is creating the correct message is also better than testing it via the controller, which really should be done as part of an integration test.
