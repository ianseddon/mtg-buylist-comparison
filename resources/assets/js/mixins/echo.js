import { forIn } from 'lodash';

export default {
    created() {
        forIn(this.getEventHandlers(), (handler, eventName) => {
            Echo.channel('test-channel')
                .listen(eventName, response => handler(response));
        });
    },
};