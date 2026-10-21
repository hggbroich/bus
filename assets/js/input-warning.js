function checkValueAndTriggerOrHideWarning($input) {
    let $warningText = $input.getAttribute('data-warning-for-input');
    let $triggerValue = $input.getAttribute('data-warning-at-input');

    if($input.value?.trim() === $triggerValue) {
        $input.classList.add('has-warning');

        if($input.nextSibling === null) {
            let $warningContainer = document.createElement('div');
            $warningContainer.classList.add('warning-feedback');
            $warningContainer.innerText = $warningText;

            $input.parentNode.insertBefore($warningContainer, $input.nextSibling);
        }
    } else {
        $input.classList.remove('has-warning');

        if($input.nextSibling !== null && $input.nextSibling.classList.contains('warning-feedback')) {
            $input.nextSibling.remove();
        }
    }
}

for(let $input of document.querySelectorAll('[data-warning-at-input]')) {
    $input.addEventListener('keyup', function() {
        checkValueAndTriggerOrHideWarning($input);
    });

    checkValueAndTriggerOrHideWarning($input);
}
