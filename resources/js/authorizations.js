let user = window.App.user;

module.exports = {
    owns(entity, prop = 'user_id') {
        return entity[prop] === user.id;
    }
}
