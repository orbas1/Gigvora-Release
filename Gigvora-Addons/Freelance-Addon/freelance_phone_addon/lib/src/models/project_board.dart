import 'project_milestone.dart';
import 'project_task.dart';
import 'project_invitation.dart';
import 'project_submission.dart';
import 'project_time_log.dart';
import 'project_review.dart';

class ProjectBoard {
  ProjectBoard({
    required this.title,
    required this.freelancers,
    required this.tasks,
    required this.milestones,
    required this.invitations,
    required this.submissions,
    required this.timeLogs,
    required this.reviews,
  });

  final String title;
  final List<String> freelancers;
  final List<ProjectTask> tasks;
  final List<ProjectMilestone> milestones;
  final List<ProjectInvitation> invitations;
  final List<ProjectSubmission> submissions;
  final List<ProjectTimeLog> timeLogs;
  final List<ProjectReview> reviews;

  factory ProjectBoard.fromJson(Map<String, dynamic>? json) {
    final data = json ?? {};
    return ProjectBoard(
      title: data['project']?['title']?.toString() ?? 'Project',
      freelancers: (data['freelancers'] as List?)?.map((e) => e['freelancer']?.toString() ?? '').where((e) => e.isNotEmpty).toList() ?? const [],
      tasks: (data['tasks'] as List?)?.map((e) => ProjectTask.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      milestones: (data['milestones'] as List?)?.map((e) => ProjectMilestone.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      invitations: (data['invitations'] as List?)?.map((e) => ProjectInvitation.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      submissions: (data['submissions'] as List?)?.map((e) => ProjectSubmission.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      timeLogs: (data['time_logs'] as List?)?.map((e) => ProjectTimeLog.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
      reviews: (data['reviews'] as List?)?.map((e) => ProjectReview.fromJson(e as Map<String, dynamic>?)).toList() ?? const [],
    );
  }
}
