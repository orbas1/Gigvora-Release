import 'package:flutter/material.dart';

import 'config.dart';
import 'package:provider/provider.dart';
import 'services/api_client.dart';
import 'services/cv_service.dart';
import 'services/cover_letter_service.dart';
import 'models/candidate.dart';
import 'models/job.dart';
import 'pages/employer/ats_board_screen.dart';
import 'pages/employer/candidate_detail_screen.dart';
import 'pages/employer/employer_billing_screen.dart';
import 'pages/employer/employer_company_profile_screen.dart';
import 'pages/employer/employer_dashboard_screen.dart';
import 'pages/employer/employer_interview_schedule_screen.dart';
import 'pages/employer/employer_jobs_list_screen.dart';
import 'pages/employer/job_create_edit_screen.dart';
import 'pages/employer/screening_config_screen.dart';
import 'pages/seeker/application_detail_screen.dart';
import 'pages/seeker/cover_letter_edit_screen.dart';
import 'pages/seeker/cv_edit_screen.dart';
import 'pages/seeker/cv_list_screen.dart';
import 'pages/seeker/job_apply_screen.dart';
import 'pages/seeker/job_detail_screen.dart';
import 'pages/seeker/job_search_screen.dart';
import 'pages/seeker/jobs_home_screen.dart';
import 'pages/seeker/my_applications_screen.dart';
import 'pages/seeker/saved_jobs_screen.dart';
import 'state/cover_letter_state.dart';
import 'state/cv_state.dart';

class MenuItem {
  const MenuItem({required this.title, required this.route, required this.icon});

  final String title;
  final String route;
  final IconData icon;
}

final seekerMenu = <MenuItem>[
  const MenuItem(title: 'Jobs', route: '/jobs/home', icon: Icons.work_outline),
  const MenuItem(title: 'Saved Jobs', route: '/jobs/saved', icon: Icons.bookmark_border),
  const MenuItem(title: 'My Applications', route: '/jobs/applications', icon: Icons.assignment_outlined),
  const MenuItem(title: 'My CVs', route: '/jobs/cv', icon: Icons.description_outlined),
];

final employerMenu = <MenuItem>[
  const MenuItem(title: 'My Jobs', route: '/employer/jobs', icon: Icons.business_center_outlined),
  const MenuItem(title: 'ATS', route: '/employer/ats', icon: Icons.dashboard_customize_outlined),
  const MenuItem(title: 'Interviews', route: '/employer/interviews', icon: Icons.event_note_outlined),
  const MenuItem(title: 'Billing', route: '/employer/billing', icon: Icons.credit_card_outlined),
];

Map<String, WidgetBuilder> buildRoutes() => {
      '/jobs/home': (_) => const JobsHomeScreen(),
      '/jobs/search': (_) => const JobSearchScreen(),
      '/jobs/detail': (context) {
        final id = ModalRoute.of(context)?.settings.arguments as int;
        return JobDetailScreen(jobId: id);
      },
      '/jobs/apply': (context) {
        final job = ModalRoute.of(context)?.settings.arguments as Job;
        return JobApplyScreen(job: job);
      },
      '/jobs/saved': (_) => const SavedJobsScreen(),
      '/jobs/applications': (_) => const MyApplicationsScreen(),
      '/jobs/applications/detail': (context) {
        final id = ModalRoute.of(context)?.settings.arguments as int;
        return ApplicationDetailScreen(applicationId: id);
      },
      '/jobs/cv': (_) => const CvListScreen(),
      '/jobs/cv/edit': (context) {
        final cv = ModalRoute.of(context)?.settings.arguments as dynamic;
        return ChangeNotifierProvider(
          create: (_) => CvState(CvService(JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()))),
          child: CvEditScreen(cv: cv as dynamic),
        );
      },
      '/jobs/cover-letter': (context) {
        final letter = ModalRoute.of(context)?.settings.arguments;
        return ChangeNotifierProvider(
          create: (_) => CoverLetterState(CoverLetterService(JobsApiClient(baseUrl: JobsAddonConfig.baseUrl, defaultHeaders: JobsAddonConfig.defaultHeaders()))),
          child: CoverLetterEditScreen(letter: letter as dynamic),
        );
      },
      '/employer/dashboard': (_) => const EmployerDashboardScreen(),
      '/employer/jobs': (_) => const EmployerJobsListScreen(),
      '/employer/jobs/create': (_) => const JobCreateEditScreen(),
      '/employer/company': (_) => const EmployerCompanyProfileScreen(),
      '/employer/ats': (context) {
        final jobId = ModalRoute.of(context)?.settings.arguments as int? ?? 0;
        return AtsBoardScreen(jobId: jobId);
      },
      '/employer/candidates/detail': (context) {
        final candidate = ModalRoute.of(context)?.settings.arguments as CandidateProfile;
        return CandidateDetailScreen(candidate: candidate);
      },
      '/employer/screening': (_) => const ScreeningConfigScreen(),
      '/employer/interviews': (_) => const EmployerInterviewScheduleScreen(),
      '/employer/billing': (_) => const EmployerBillingScreen(),
    };

class JobsAddon {
  static void configure({required String baseUrl, String? token}) {
    JobsAddonConfig.baseUrl = baseUrl;
    JobsAddonConfig.authToken = token;
  }
}
