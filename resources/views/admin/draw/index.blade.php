
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-600 leading-tight bg-gradient">
                {{ __('Draw List') }}
            </h2>
            @if(Auth::user()->role == "super_admin")
                <a href="{{route('admin.draws.create')}}" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-md px-4 py-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Create Draw</a>
            @endif
        </div>
    </x-slot>

    <div>
        <div><div id='calendar'></div></div>
    </div>

    <style>
        .fc-widget-header{
            background-color:#80B3FF;
        }
       
    </style>

    {{-- usable cdn for FullCalendar --}}
    <script src='http://fullcalendar.io/js/fullcalendar-2.6.1/lib/moment.min.js'></script>
    <script src='http://fullcalendar.io/js/fullcalendar-2.6.1/lib/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src='http://fullcalendar.io/js/fullcalendar-2.6.1/fullcalendar.min.js'></script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.6.1/fullcalendar.min.css">
    <!-- Include the FullCalendar interaction plugin script -->

    <script>
        $(document).ready(function() {

            function getDrawList(){
                let url = "{{ route('admin.draws.calendar_list') }}"

                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        _method: "GET",
                    },
                    success: function(responses){
                        const events = [];
                        Object.values(responses).forEach(data => {
                            const title = (data.is_special_draw == 0) ? 'Ordinary Draw' : 'Special Draw';
                            const dateWithTime = data.expired_at;
                            const [date] = dateWithTime.split(' ');

                            let draw = {
                                title: title,
                                date: date,
                                backgroundColor: (data.is_special_draw == 0) ? '#435585' : '#FF5B22',
                                borderColor: 'blue',
                                classNames: 'tag_style',
                                url: 'draws/special-draw-tickets/'+date
                            };
                            events.push(draw);
                        });
                        $('#calendar').fullCalendar('addEventSource', events);
                    },

                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            getDrawList();

            $('#calendar').fullCalendar({

                initialView: 'dayGridMonth',
                selectable: true,
                editable: true,
                eventLimit: true,
                buttonText: {
                    today: 'Today',
                },
                events: [],
                dayClick: function(date) {
                    const datestr = date.toISOString();
                    window.location.href = `draws/special-draw-tickets/${datestr}`;
                }
            });




        });
    </script>


</x-app-layout>