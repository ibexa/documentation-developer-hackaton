---
description: Learn how to use collaborative editing to work with team members on content creation and editing.
---

# Using collaborative editing

This guide walks you through the collaborative editing features, from creating your first collaboration session to managing shared drafts and working with real-time editing.

## Starting a collaboration session

### From content editing

1. **Open content for editing**: Navigate to any content item and click "Edit"
2. **Start collaboration**: Look for the "Collaborate" or "Share" button in the toolbar
3. **Configure session**: 
   - Set session name (optional)
   - Choose collaboration type (view-only, edit, or full access)
   - Set expiration date
4. **Invite participants**: Add users by username, email, or user group

![Starting collaboration from content edit](img/start_collaboration.png "Starting collaboration from content edit")

### From draft management

1. **Navigate to drafts**: Go to your dashboard and select "My drafts"
2. **Share existing draft**: Click the share icon next to any draft
3. **Configure sharing options**: Set permissions and expiration
4. **Send invitations**: Choose recipients and send invitations

## Managing collaboration sessions

### Session dashboard

Access your collaboration sessions through the dedicated dashboard tabs:

- **My shared drafts**: Content you've shared with others
- **Drafts shared with me**: Content others have shared with you

![Collaboration dashboard](img/collaboration_dashboard.png "Collaboration dashboard")

The dashboard shows:
- Session status (active, expired, completed)
- Number of participants  
- Last activity timestamp
- Quick actions (edit, manage, end session)

### Session details

Click on any session to view detailed information:

- **Participants list**: Who's currently in the session
- **Activity log**: Recent changes and comments
- **Session settings**: Permissions and expiration details
- **Actions**: End session, modify permissions, export changes

## Working with invitations

### Sending invitations

When starting a collaboration session, you can invite:

**Internal users**:
- Search by username or display name
- Select from user group membership
- Assign specific roles (viewer, editor, reviewer)

**External users**:
- Enter email addresses
- Set limited access permissions
- Configure session duration (typically shorter than internal users)

![Invitation interface](img/send_invitations.png "Invitation interface")

### Receiving invitations

When someone invites you to collaborate:

1. **Email notification**: You'll receive an invitation email with session details
2. **Dashboard notification**: New sessions appear in "Drafts shared with me"
3. **Join session**: Click the invitation link or join from the dashboard
4. **Accept terms**: Review and accept collaboration terms (if configured)

### Managing received invitations

From the "Drafts shared with me" tab:

- **Join active sessions**: Click "Join" to enter collaboration mode
- **View session info**: See who else is participating and session details
- **Leave sessions**: Exit collaboration if no longer needed
- **Notifications**: Configure how you want to be notified of session activity

## Real-time collaboration features

### Live editing

When real-time editing is enabled, you'll see:

**User cursors**: Live cursors showing where other users are currently editing

**Instant updates**: Changes appear immediately as other users type

**Conflict indicators**: Visual warnings when multiple users edit the same area

**User presence**: List of currently active users in the session

![Real-time editing interface](img/realtime_editing.png "Real-time editing interface")

### Working asynchronously

Even without real-time editing, you can collaborate effectively:

**Change tracking**: All modifications are tracked with user attribution

**Comment system**: Leave comments on specific content sections

**Version comparison**: See what changed between your last visit

**Merge assistance**: Guided merge process when conflicts occur

## Collaboration workflow examples

### Content review workflow

**Scenario**: Blog post requiring legal and technical review

1. **Author creates draft**: Marketing team writes initial blog post
2. **Share for review**: Author shares draft with legal and technical teams as "reviewers"
3. **Parallel review**: Both teams can review simultaneously, leaving comments
4. **Author incorporates feedback**: Marketing team addresses comments and updates content
5. **Final approval**: Reviewers confirm changes and approve for publication
6. **Publish**: Author publishes the final version

### External collaboration

**Scenario**: Working with external consultants on documentation

1. **Create collaboration session**: Internal team creates draft and shares with consultants
2. **Time-limited access**: External users get 4-hour access windows
3. **Guided collaboration**: Internal users facilitate sessions and provide context
4. **Knowledge transfer**: External experts contribute specialized content
5. **Internal review**: Internal team reviews and finalizes content before publication

### Multi-team content creation

**Scenario**: Product announcement requiring input from multiple departments

1. **Initial structure**: Product marketing creates content outline
2. **Parallel contribution**: Technical writers, designers, and developers contribute simultaneously
3. **Real-time coordination**: Teams see each other's work and coordinate in real-time
4. **Review and approval**: Management reviews consolidated content
5. **Launch coordination**: All teams finalize their sections before coordinated publication

## Comments and feedback

### Leaving comments

Add comments to specific content sections:

1. **Select text**: Highlight the content you want to comment on
2. **Add comment**: Click the comment button or use keyboard shortcut
3. **Write feedback**: Provide constructive feedback or ask questions
4. **Tag participants**: Use @mentions to notify specific users
5. **Submit**: Comment is immediately visible to all participants

### Managing comments

**Reply to comments**: Continue the conversation by replying to existing comments

**Resolve comments**: Mark comments as resolved once addressed

**Filter comments**: View only your comments, unresolved issues, or all feedback

**Export comments**: Download comment summary for external review

![Comment system](img/collaboration_comments.png "Comment system interface")

## Session management

### Ending sessions

**Manual termination**: End active sessions when collaboration is complete

**Automatic expiration**: Sessions end automatically based on configured timeouts

**Graceful shutdown**: All participants receive notifications before session ends

**Data preservation**: Comments and change history are preserved after session ends

### Permissions during collaboration

Participants can have different permission levels:

**Viewer**: Can see content and leave comments but cannot edit

**Editor**: Can modify content and participate fully in collaboration

**Reviewer**: Can see changes, comment, and approve/reject modifications

**Administrator**: Full control over session, including ending and managing participants

### Monitoring activity

Track collaboration progress through:

**Activity feeds**: Real-time updates on participant actions

**Change summaries**: Consolidated view of all modifications made during session

**Participation metrics**: See who's contributing most actively

**Time tracking**: Monitor how long users spend in sessions

## Best practices

### Before starting collaboration

- **Define objectives**: Clearly communicate what you want to achieve
- **Set expectations**: Let participants know their roles and responsibilities  
- **Prepare content**: Have a solid foundation before inviting others
- **Check permissions**: Ensure all participants have necessary access rights

### During collaboration

- **Stay focused**: Keep discussions relevant to the content being developed
- **Use comments effectively**: Provide specific, actionable feedback
- **Communicate changes**: Explain significant modifications you're making
- **Respect others' work**: Don't overwrite others' contributions without discussion

### After collaboration

- **Review changes**: Check all modifications before finalizing content
- **Archive sessions**: Properly close sessions when work is complete
- **Document decisions**: Preserve important discussion points and decisions
- **Follow up**: Ensure all feedback has been addressed

## Troubleshooting common issues

### Connection problems

**Real-time sync issues**: Refresh the page or rejoin the session

**Missing updates**: Check your internet connection and session status

**Performance slowdown**: Reduce number of active participants or use asynchronous mode

### Permission problems

**Cannot edit content**: Verify you have edit permissions for the underlying content

**Cannot invite users**: Check if you have collaboration/invite policy permissions

**Access denied errors**: Ensure your user role includes necessary collaboration policies

### Session management issues

**Session expired**: Check expiration settings and renew if necessary

**Cannot join session**: Verify invitation is still valid and you're logged in

**Missing shared drafts**: Check if content has been moved or permissions changed

## Next steps

- [Configure advanced permissions](collaborative_editing_permissions.md)
- [Explore the PHP API](collaborative_editing_api.md)  
- [Set up custom notifications](collaborative_editing_events.md)