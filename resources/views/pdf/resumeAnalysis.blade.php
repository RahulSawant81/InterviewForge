<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Resume Analysis Report</title>
        <style>
            /* Reset margins */
            body, h1, h3, p, table, tr, td, div, ul, li {
                margin: 0;
                padding: 0;
            }

            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 13px;
                color: #333;
                line-height: 1.4;
                padding: 10px;
            }

            .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 10px; }
            .header h1 { color: #2563eb; font-size: 24px; margin-bottom: 5px; }
            .header h3 { color: #6b7280; font-size: 14px; }

            /* Cards - reduce bottom margin for tighter fit */
            .card {
                border: 1px solid #dbeafe;
                border-radius: 8px;
                background: #ffffff;
                margin-bottom: 8px; /* Reduced from 12px */
                page-break-inside: auto;
            }

            .section-title {
                background: #eff6ff;
                color: #1d4ed8;
                padding: 8px 12px;
                font-size: 14px;
                font-weight: bold;
                border-bottom: 1px solid #dbeafe;
            }

            .section-body {
                padding: 8px 12px; /* Reduced vertical padding */
                line-height: 1.4;
            }

            /* Fix for the Summary to Skills gap */
            .skills-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: -5px; /* Pulls the table up closer to the card above */
            }

            .skills-table td { width: 33%; padding: 0 4px; vertical-align: top; }

            .info-table { width: 100%; border-collapse: collapse; }
            .info-table td { padding: 4px 6px; border-bottom: 1px solid #edf2f7; }

            .grid { width: 100%; border-collapse: collapse; margin-top: 0; }
            .grid td { width: 50%; vertical-align: top; padding: 0 4px; }

            .list { margin: 0; padding-left: 16px; list-style-type: disc; }
            .list li { margin-bottom: 2px; }

            .footer {
                margin-top: 10px;
                border-top: 1px solid #d1d5db;
                padding-top: 5px;
                text-align: center;
                font-size: 10px;
                color: #6b7280;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>InterviewForge</h1>
            <h3>AI Resume Analysis Report</h3>
        </div>
        <div class="card">
            <div class="section-title">
                <img
                    src="{{ public_path('images/icons/file-text.svg') }}"
                    alt=""
                />
                Resume Information
            </div>
            <div class="section-body">
                <table class="info-table">
                    <tr>
                        <td class="info-label">Resume Title</td>
                        <td>{{ $resume->title }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Original File</td>
                        <td>{{ $resume->original_filename }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Generated On</td>
                        <td>{{ now()->format('d M Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @php
            $score = (int) $analysis->overall_score;

            if ($score >= 90) {
                $status = 'Excellent Match';
                $color = '#16a34a';
            } elseif ($score >= 75) {
                $status = 'Good Match';
                $color = '#2563eb';
            } elseif ($score >= 60) {
                $status = 'Average Match';
                $color = '#ea580c';
            } else {
                $status = 'Needs Improvement';
                $color = '#dc2626';
            }
        @endphp
        <div class="card">

            <div class="section-title">
                <img
                    src="{{ public_path('images/icons/target.svg') }}"
                    alt=""
                />
                ATS Score
            </div>

            <div class="section-body">

                <div class="score-card">

                    <div
                        class="score-number"
                        style="color: {{ $color }};"
                    >
                        {{ $score }}%
                    </div>

                    <div class="score-divider"></div>

                    <div class="score-label">
                        {{ $status }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="section-title">
                <img src="{{ public_path('images/icons/brain.svg') }}" alt=""/>
                AI Executive Summary
            </div>
            <div class="section-body">
                <p class="summary-text">
                    {{ $analysis->summary ?? 'Summary not available.' }}
                </p>
            </div>
        </div>
        <table class="skills-table">
            <tr>
                <td>
                    <div class="card">
                        <div class="section-title">
                            <img src="{{ public_path('images/icons/badge-check.svg') }}" alt=""/>
                            Skills Found ({{ count($analysis->skills) }})
                        </div>
                        <div class="section-body">
                            @if(count($analysis->skills))
                                <ul class="list">
                                    @foreach($analysis->skills as $skill)
                                        <li>{{ $skill }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No skills identified.</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="section-title">
                            <img src="{{ public_path('images/icons/circle-off.svg') }}" alt=""/>
                            Missing Skills
                            ({{ count($analysis->missing_skills) }})
                        </div>
                        <div class="section-body">
                            @if(count($analysis->missing_skills))
                                <ul class="list">
                                    @foreach($analysis->missing_skills as $skill)
                                        <li>{{ $skill }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No missing skills identified.</p>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table class="grid">
            <tr>
                <td>
                    <div class="card">
                        <div class="section-title">
                            <img src="{{ public_path('images/icons/square-check-big.svg') }}" alt=""/>
                            Strengths ({{ count($analysis->strengths) }})
                        </div>
                        <div class="section-body">
                            @if(count($analysis->strengths))
                                <ul class="list">
                                    @foreach($analysis->strengths as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No strengths available.</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="card">
                        <div class="section-title">
                            <img
                                src="{{ public_path('images/icons/triangle-alert.svg') }}"
                                alt=""
                            />
                            Weaknesses ({{ count($analysis->weaknesses) }})
                        </div>
                        <div class="section-body">
                            @if(count($analysis->weaknesses))
                                <ul class="list">

                                    @foreach($analysis->weaknesses as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No weaknesses available.</p>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="card">
            <div class="section-title">
                <img src="{{ public_path('images/icons/lightbulb.svg') }}" alt="" />
                Recommendations ({{ count($analysis->recommendations) }})
            </div>
            <div class="section-body">
                @if(count($analysis->recommendations))
                    <ul class="list">
                        @foreach($analysis->recommendations as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No recommendations available.</p>
                @endif
            </div>
        </div>
        <div class="footer">
            <strong>InterviewForge AI</strong>
            <br><br>
            Confidential Resume Assessment Report
            <br>
            Generated on {{ now()->format('d M Y h:i A') }}
        </div>
    </body>
</html>
