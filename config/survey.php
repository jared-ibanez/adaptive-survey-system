<?php
/**
 * Laravel-ready interpretation arrays aligned to the Word document:
 * - Dimensional Interpretation (0–15): 5 levels
 * - Overall Adaptive Competency (0–90): 5 levels
 *
 * Put this into: config/survey.php
 * Then run: php artisan config:clear
 */

return [

    'dimensions' => [

        // Dimension 1: Decision Making (Items 1–5) | Score range: 0–15
        'decision_making' => [
            'label' => 'Decision Making',
            'items' => '1–5',
            'min' => 0,
            'max' => 15,
            'bands' => [
                'highly_competent' => [
                    'label' => 'Highly Competent',
                    'range' => [13, 15],
                    'title' => 'Highly competent decision-making',
                    'text'  => 'You consistently demonstrate strong and independent decision-making. You seek clarification when needed, evaluate options carefully, manage stress effectively, and choose actions that align with goals and priorities.',
                ],
                'competent' => [
                    'label' => 'Competent',
                    'range' => [10, 12],
                    'title' => 'Competent decision-making',
                    'text'  => 'You usually make reasonable decisions and show good judgment in many situations. You often consider options before acting and can manage most demands with minimal support.',
                ],
                'developing' => [
                    'label' => 'Developing',
                    'range' => [7, 9],
                    'title' => 'Developing decision-making',
                    'text'  => 'You show emerging decision-making skills but may be inconsistent. At times, you may act quickly, rely on others, or need more planning and reflection to make better choices.',
                ],
                'low' => [
                    'label' => 'Low',
                    'range' => [4, 6],
                    'title' => 'Low decision-making',
                    'text'  => 'You show limited decision-making skills and may struggle to evaluate options or plan ahead. You may need structured guidance to improve how you respond to challenging situations.',
                ],
                'needs_significant_support' => [
                    'label' => 'Needs Significant Support',
                    'range' => [0, 3],
                    'title' => 'Needs significant support in decision-making',
                    'text'  => 'You demonstrate minimal decision-making skills at this time. You may avoid decisions, guess, or rely heavily on others. Substantial support and coaching are recommended to build this competency.',
                ],
            ],
        ],

        // Dimension 2: Teamwork & Respect (Items 6–10) | Score range: 0–15
        'teamwork_respect' => [
            'label' => 'Teamwork & Respect',
            'items' => '6–10',
            'min' => 0,
            'max' => 15,
            'bands' => [
                'highly_competent' => [
                    'label' => 'Highly Competent',
                    'range' => [13, 15],
                    'title' => 'Highly competent teamwork and respect',
                    'text'  => 'You consistently demonstrate respectful communication and strong collaboration. You respond calmly, support others, handle disagreement constructively, and accept feedback in a mature and helpful way.',
                ],
                'competent' => [
                    'label' => 'Competent',
                    'range' => [10, 12],
                    'title' => 'Competent teamwork and respect',
                    'text'  => 'You usually work well with others and show respect in most situations. You cooperate appropriately and can maintain positive interactions with minimal support.',
                ],
                'developing' => [
                    'label' => 'Developing',
                    'range' => [7, 9],
                    'title' => 'Developing teamwork and respect',
                    'text'  => 'You show emerging teamwork skills but may be inconsistent. You may need guidance in communication, patience, or managing emotions when working with others.',
                ],
                'low' => [
                    'label' => 'Low',
                    'range' => [4, 6],
                    'title' => 'Low teamwork and respect',
                    'text'  => 'You show limited teamwork and respectful interaction in several situations. You may benefit from structured support to strengthen cooperation, empathy, and respectful communication.',
                ],
                'needs_significant_support' => [
                    'label' => 'Needs Significant Support',
                    'range' => [0, 3],
                    'title' => 'Needs significant support in teamwork and respect',
                    'text'  => 'You demonstrate minimal teamwork and respectful behaviors at this time. Significant guidance and consistent practice are recommended to build effective collaboration and respectful interaction.',
                ],
            ],
        ],

        // Dimension 3: Learning Skills (Items 11–15) | Score range: 0–15
        'learning_skills' => [
            'label' => 'Learning Skills',
            'items' => '11–15',
            'min' => 0,
            'max' => 15,
            'bands' => [
                'highly_competent' => [
                    'label' => 'Highly Competent',
                    'range' => [13, 15],
                    'title' => 'Highly competent learning skills',
                    'text'  => 'You consistently use effective learning strategies. You seek clarification, use reliable sources, apply structured study methods, and monitor your understanding to improve performance.',
                ],
                'competent' => [
                    'label' => 'Competent',
                    'range' => [10, 12],
                    'title' => 'Competent learning skills',
                    'text'  => 'You generally show good learning habits and use helpful strategies in many situations. You can study effectively with minimal support, though improvements in consistency may still help.',
                ],
                'developing' => [
                    'label' => 'Developing',
                    'range' => [7, 9],
                    'title' => 'Developing learning skills',
                    'text'  => 'You show emerging study and learning strategies but may be inconsistent. You may need guidance to strengthen planning, strategy use, and self-monitoring.',
                ],
                'low' => [
                    'label' => 'Low',
                    'range' => [4, 6],
                    'title' => 'Low learning skills',
                    'text'  => 'You show limited learning strategies and may struggle to study effectively. You may benefit from structured support and explicit skill-building in study habits and learning techniques.',
                ],
                'needs_significant_support' => [
                    'label' => 'Needs Significant Support',
                    'range' => [0, 3],
                    'title' => 'Needs significant support in learning skills',
                    'text'  => 'You demonstrate minimal effective learning strategies at this time. Substantial guidance is recommended to build study routines, resource use, and self-regulation in learning.',
                ],
            ],
        ],

        // Dimension 4: Responsibility (Items 16–20) | Score range: 0–15
        'responsibility' => [
            'label' => 'Responsibility',
            'items' => '16–20',
            'min' => 0,
            'max' => 15,
            'bands' => [
                'highly_competent' => [
                    'label' => 'Highly Competent',
                    'range' => [13, 15],
                    'title' => 'Highly competent responsibility',
                    'text'  => 'You consistently show strong responsibility and accountability. You meet deadlines, follow instructions carefully, correct mistakes promptly, and take initiative to prevent problems from happening again.',
                ],
                'competent' => [
                    'label' => 'Competent',
                    'range' => [10, 12],
                    'title' => 'Competent responsibility',
                    'text'  => 'You generally show responsible behavior. You complete tasks properly and meet expectations in most situations, though consistency and proactive follow-through can still be improved.',
                ],
                'developing' => [
                    'label' => 'Developing',
                    'range' => [7, 9],
                    'title' => 'Developing responsibility',
                    'text'  => 'You show emerging responsibility but may be inconsistent in follow-through. You may need reminders or support to stay organized, meet deadlines, and respond to mistakes appropriately.',
                ],
                'low' => [
                    'label' => 'Low',
                    'range' => [4, 6],
                    'title' => 'Low responsibility',
                    'text'  => 'You show limited responsibility behaviors in several areas. You may benefit from structured guidance to strengthen accountability, task completion, and follow-through.',
                ],
                'needs_significant_support' => [
                    'label' => 'Needs Significant Support',
                    'range' => [0, 3],
                    'title' => 'Needs significant support in responsibility',
                    'text'  => 'You demonstrate minimal responsibility behaviors at this time. Significant support is recommended to build accountability, consistency, and task completion skills.',
                ],
            ],
        ],

        // Dimension 5: Flexible Thinking (Items 21–25) | Score range: 0–15
        'flexible_thinking' => [
            'label' => 'Flexible Thinking',
            'items' => '21–25',
            'min' => 0,
            'max' => 15,
            'bands' => [
                'highly_competent' => [
                    'label' => 'Highly Competent',
                    'range' => [13, 15],
                    'title' => 'Highly competent flexible thinking',
                    'text'  => 'You consistently adapt effectively to change and uncertainty. You adjust quickly, remain productive during disruptions, and help others cope with changes in plans or expectations.',
                ],
                'competent' => [
                    'label' => 'Competent',
                    'range' => [10, 12],
                    'title' => 'Competent flexible thinking',
                    'text'  => 'You generally adapt well to changes in most situations. You can adjust your plans and continue tasks appropriately, though you may still need time to fully settle into unexpected changes.',
                ],
                'developing' => [
                    'label' => 'Developing',
                    'range' => [7, 9],
                    'title' => 'Developing flexible thinking',
                    'text'  => 'You show emerging flexibility but may feel uncomfortable when changes occur. You may need guidance to respond more positively and to adjust more quickly.',
                ],
                'low' => [
                    'label' => 'Low',
                    'range' => [4, 6],
                    'title' => 'Low flexible thinking',
                    'text'  => 'You show limited flexibility and may struggle when plans change. You may benefit from structured support and practice in adjusting strategies and responding calmly to change.',
                ],
                'needs_significant_support' => [
                    'label' => 'Needs Significant Support',
                    'range' => [0, 3],
                    'title' => 'Needs significant support in flexible thinking',
                    'text'  => 'You demonstrate minimal flexibility at this time. Significant guidance is recommended to build coping strategies and adaptive responses to change and disruption.',
                ],
            ],
        ],

        // Dimension 6: Critical & Creative Thinking (Items 26–30) | Score range: 0–15
        'critical_creative' => [
            'label' => 'Critical & Creative Thinking',
            'items' => '26–30',
            'min' => 0,
            'max' => 15,
            'bands' => [
                'highly_competent' => [
                    'label' => 'Highly Competent',
                    'range' => [13, 15],
                    'title' => 'Highly competent critical and creative thinking',
                    'text'  => 'You consistently demonstrate strong reasoning and creativity. You analyze problems deeply, interpret information accurately, compare ideas meaningfully, and generate well-justified solutions.',
                ],
                'competent' => [
                    'label' => 'Competent',
                    'range' => [10, 12],
                    'title' => 'Competent critical and creative thinking',
                    'text'  => 'You generally show good thinking and problem-solving skills. You can analyze and generate solutions in most situations, though you may still benefit from deeper justification or more creative exploration.',
                ],
                'developing' => [
                    'label' => 'Developing',
                    'range' => [7, 9],
                    'title' => 'Developing critical and creative thinking',
                    'text'  => 'You show emerging thinking skills but may be inconsistent. You may need guidance to analyze more deeply, interpret information accurately, and generate stronger or more creative solutions.',
                ],
                'low' => [
                    'label' => 'Low',
                    'range' => [4, 6],
                    'title' => 'Low critical and creative thinking',
                    'text'  => 'You show limited higher-order thinking skills. You may rely on surface details or basic ideas and may benefit from structured practice and support in analysis and creative problem-solving.',
                ],
                'needs_significant_support' => [
                    'label' => 'Needs Significant Support',
                    'range' => [0, 3],
                    'title' => 'Needs significant support in critical and creative thinking',
                    'text'  => 'You demonstrate minimal critical and creative thinking skills at this time. Significant guidance is recommended to develop analysis, interpretation, and creative solution-building skills.',
                ],
            ],
        ],
    ],

    'overall' => [
        'label' => 'Overall Adaptive Competency Interpretation',
        'min' => 0,
        'max' => 90,
        'bands' => [
            'highly_competent' => [
                'label' => 'Highly Competent',
                'range' => [73, 90],
                'title' => 'Highly competent overall adaptive competency',
                'text'  => 'You demonstrate consistently strong adaptive competency across dimensions. You show independence, sound judgment, and effective strategies for learning, collaboration, responsibility, flexibility, and problem-solving.',
            ],
            'competent' => [
                'label' => 'Competent',
                'range' => [55, 72],
                'title' => 'Competent overall adaptive competency',
                'text'  => 'You show generally effective adaptive competency across dimensions. You demonstrate appropriate skills in most situations, with only occasional need for improvement or support in specific areas.',
            ],
            'developing' => [
                'label' => 'Developing',
                'range' => [37, 54],
                'title' => 'Developing overall adaptive competency',
                'text'  => 'You show emerging adaptive competency, but skills may be inconsistent across situations. Continued practice and structured guidance can help strengthen your overall performance.',
            ],
            'low' => [
                'label' => 'Low',
                'range' => [19, 36],
                'title' => 'Low overall adaptive competency',
                'text'  => 'You show limited adaptive competency across dimensions and may require consistent support. Strengthening core skills and routines can improve your overall adaptive functioning.',
            ],
            'needs_significant_support' => [
                'label' => 'Needs Significant Support',
                'range' => [0, 18],
                'title' => 'Needs significant support overall',
                'text'  => 'You demonstrate significant gaps in adaptive competency and require substantial support. Focused coaching and structured interventions are recommended to build foundational skills across dimensions.',
            ],
        ],
    ],

];
