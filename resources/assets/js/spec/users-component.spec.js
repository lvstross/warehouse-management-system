import Vue from 'vue';
import usersComponent from '../components/users.vue';

describe('usersComponent', function() {
    it('has a mounted hook', function() {
        expect(typeof usersComponent.mounted).toBe('function');
    });
});
