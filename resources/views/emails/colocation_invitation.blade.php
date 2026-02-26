<p>You have been invited to join the colocation: {{ $colocationName }}.</p>

<p>
    Click the link to join:
    <a href="{{ route('invitations.acceptForm', $token) }}">
        Accept Invitation
    </a>
</p>