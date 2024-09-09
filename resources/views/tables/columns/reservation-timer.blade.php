<div class="timer" x-data="{
    endDate: new Date(@js($getRecord()->reservation_expiry)).getTime(),
    remainingTime: 1,
    formatTime(time) {
        Number.prototype.zeroPad = function(length) {
            length = length || 2; // defaults to 2 if no parameter is passed
            return (new Array(length).join('0') + this).slice(length * -1);
        };
        const hours = Math.floor((time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).zeroPad();
        const minutes = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60)).zeroPad();
        const seconds = Math.floor((time % (1000 * 60)) / 1000).zeroPad();
        return { hours, minutes, seconds };
    }
}" x-init="() => {
    setInterval(() => {
        const now = new Date().getTime();
        const remainingTime = endDate - now;
        $data.remainingTime = remainingTime > 0 ? remainingTime : 0;
    }, 1000);
}">
    <template x-if="remainingTime > 0">
        <div class="timer gap-1">
            <h1 x-text="`${formatTime(remainingTime).hours}`">
            </h1>
            <h1 x-text="`${formatTime(remainingTime).minutes}`">
            </h1>
            <h1 x-text="`${formatTime(remainingTime).seconds}`">
            </h1>

        </div>
    </template>
    <template x-if="remainingTime <= 0">
        <h1 x-text="`Expired`"></h1>
    </template>
</div>
