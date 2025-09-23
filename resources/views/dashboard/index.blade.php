@extends('layouts.app')

@section('content')
    <!-- Título e Trilha de Navegação -->
    <div class="content-wrapper">
        <div class="content-header">
            <h2 class="content-title">{{ pageTitle() }}</h2>
            {!! renderBreadcrumb() !!}
        </div>
    </div>

    <div class="content-box">
        <!-- inicio  Content box header -->
        <div class="content-box-header">
            <h3 class="content-box-title">{{ __('Dashboard') }}</h3>
            <div class="content-box-btn"></div>
        </div>
        <!-- fim  Content box header -->

        <x-alert />

        <!-- Inicio do conteudo -->

        <div class="flex flex-wrap justify-between w-full gap-4">
            <div class="flex-1 min-w-[300px] box-cont ">
                <canvas id="barChart" class="w-full h-72"></canvas>
            </div>


            <div class="flex-1 min-w-[300px] box-cont">
                <canvas id="lineChart" class="w-full h-72"></canvas>
            </div>
        </div>
        <!-- Fim do conteudo -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('barChart');

            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Novos Cadastros',
                            data: @json($data),
                            backgroundColor: '#4ab3c6',
                            //  borderColor: '#32a2b910',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        resposive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('lineChart');

            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Novos Cadastros',
                            data: @json($data),
                            backgroundColor: '#32a2b925',
                            borderColor: '#276377',
                            border: 2,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: '#276377',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        resposive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        })
    </script>
@endsection
