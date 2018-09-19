const lotDaysColor = 'green';
const fewDaysColor = 'orange';
const noDaysColor = 'red';

export default {
    inserted: function (el, bindings) {
        if (bindings.value) {
            let arg = bindings.arg;
            let dateExpiration = null;

            if (bindings.value.expiredAt) {
                dateExpiration = bindings.value.expiredAt.YmdHis;
            } else {
                return;
            }

            let daysLeft = (new Date(dateExpiration) - new Date()) / (1000 * 60 * 60 * 24);
            let borderModifier = bindings.modifiers['border'];
            let currentColor;

            if (daysLeft >= 61) {
                currentColor = lotDaysColor;
            } else if (daysLeft > 0 && daysLeft < 61) {
                currentColor = fewDaysColor;
            } else {
                currentColor = noDaysColor;
            }
            if (borderModifier) {
                el.style.border = currentColor;
            }

            el.style[arg] = currentColor;
        }
    }
};
