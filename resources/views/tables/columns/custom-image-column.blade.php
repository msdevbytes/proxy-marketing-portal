<div>

    <div class="fi-ta-image px-3 py-4" x-on:click="SimpleLightBox.open(event, '')">
        <!--[if BLOCK]><![endif]-->
        <div class="flex items-center gap-x-2.5">
            <div class="flex gap-1.5">
                <a href="{{ asset('storage/' . $getState()) }}" title="Product Image">
                    <img style="height: 2.5rem; width: 2.5rem;"
                        class="max-w-none object-cover object-center ring-white dark:ring-gray-900  simple-light-box-img-indicator"
                        src="{{ asset('storage/' . $getState()) }}" />

                </a>

                <!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
        </div>
        <!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="lightbox">

    </div>


</div>
