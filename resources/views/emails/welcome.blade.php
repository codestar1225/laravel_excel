Hi {{$user->name}}!

<p>Congratulations and welcome to FELTA LONDON CAPITAL</p>

<p>You have been successfully registered with Felta London Capital on {{Carbon\Carbon::now()->format('Y-m-d')}}. Please view your account details below:</p>

<p>User ID: {{$user->username}}<br>
Password : {{$rawpassword}}<br>
Security Pin: {{$user->withdrawkey}}<br>
Login Website: <a href="www.feltacoin.com">www.feltacoin.com</a><br>
</p>
<p>For security purpose , you are strongly advised to change your login password and security password immediately after your first login.</p>
<p>If you need help at any time or if you have any questions , do feel free to contact us by replying to this message or email us at <a href="mailto:support@feltacoin.com">support@feltacoin.com</a> directly.</p>
<p>&nbsp;</p>
<p>Regards,<br>Felta London Capital Support Team</p>

<p>For any enquires please contact us at <a href="mailto:support@feltacoin.com">support@feltacoin.com</a></p>