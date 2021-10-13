import Event from './event';

Echo.join('chat')
    .here(users => {
        Event.$emit('users.here', users);
    })
    .joining(user => {
        Event.$emit('users.joined', user);
    })
    .leaving(user => {
        Event.$emit('users.left', user);
    })
    .listen('MessageCreated', (data) => {
        Event.$emit('message.created', data.message);
    })
    .listen('ScoreUpdated', (data) => {
        Event.$emit('users.score', data.user);
    });
