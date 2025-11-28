class TalentAiFeatureFlags {
  final bool headhunters;
  final bool launchpad;
  final bool aiWorkspace;
  final bool volunteering;

  const TalentAiFeatureFlags({
    this.headhunters = true,
    this.launchpad = true,
    this.aiWorkspace = true,
    this.volunteering = true,
  });
}

class PaginatedResult<T> {
  final List<T> data;
  final int? currentPage;
  final int? lastPage;
  final int? total;

  PaginatedResult({
    required this.data,
    this.currentPage,
    this.lastPage,
    this.total,
  });
}

class HeadhunterProfileModel {
  final int id;
  final String headline;
  final String agency;
  final String focusAreas;
  final int mandatesCount;

  HeadhunterProfileModel({
    required this.id,
    required this.headline,
    required this.agency,
    required this.focusAreas,
    required this.mandatesCount,
  });

  factory HeadhunterProfileModel.fromJson(Map<String, dynamic> json) {
    return HeadhunterProfileModel(
      id: json['id'] as int? ?? 0,
      headline: json['headline'] as String? ?? '',
      agency: json['agency'] as String? ?? '',
      focusAreas: json['focus_areas'] as String? ?? '',
      mandatesCount: json['mandates_count'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'headline': headline,
        'agency': agency,
        'focus_areas': focusAreas,
        'mandates_count': mandatesCount,
      };
}

class HeadhunterMandateModel {
  final int id;
  final String title;
  final String location;
  final String status;
  final int candidateCount;
  final DateTime? createdAt;

  HeadhunterMandateModel({
    required this.id,
    required this.title,
    required this.location,
    required this.status,
    required this.candidateCount,
    this.createdAt,
  });

  factory HeadhunterMandateModel.fromJson(Map<String, dynamic> json) {
    return HeadhunterMandateModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      location: json['location'] as String? ?? '',
      status: json['status'] as String? ?? 'draft',
      candidateCount: json['candidate_count'] as int? ?? 0,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'location': location,
        'status': status,
        'candidate_count': candidateCount,
        'created_at': createdAt?.toIso8601String(),
      };
}

class HeadhunterCandidateModel {
  final int id;
  final String name;
  final String email;
  final String stage;
  final String notes;

  HeadhunterCandidateModel({
    required this.id,
    required this.name,
    required this.email,
    required this.stage,
    required this.notes,
  });

  factory HeadhunterCandidateModel.fromJson(Map<String, dynamic> json) {
    return HeadhunterCandidateModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      email: json['email'] as String? ?? '',
      stage: json['stage'] as String? ?? 'sourced',
      notes: json['notes'] as String? ?? '',
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'email': email,
        'stage': stage,
        'notes': notes,
      };
}

class HeadhunterPipelineItemModel {
  final int id;
  final HeadhunterCandidateModel candidate;
  final String stage;
  final DateTime? updatedAt;

  HeadhunterPipelineItemModel({
    required this.id,
    required this.candidate,
    required this.stage,
    this.updatedAt,
  });

  factory HeadhunterPipelineItemModel.fromJson(Map<String, dynamic> json) {
    return HeadhunterPipelineItemModel(
      id: json['id'] as int? ?? 0,
      candidate: HeadhunterCandidateModel.fromJson(
          (json['candidate'] as Map<String, dynamic>?) ?? json),
      stage: json['stage'] as String? ?? 'sourced',
      updatedAt: json['updated_at'] != null
          ? DateTime.tryParse(json['updated_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'candidate': candidate.toJson(),
        'stage': stage,
        'updated_at': updatedAt?.toIso8601String(),
      };
}

class HeadhunterInterviewModel {
  final int id;
  final int candidateId;
  final DateTime scheduledAt;
  final String mode;
  final String notes;

  HeadhunterInterviewModel({
    required this.id,
    required this.candidateId,
    required this.scheduledAt,
    required this.mode,
    required this.notes,
  });

  factory HeadhunterInterviewModel.fromJson(Map<String, dynamic> json) {
    return HeadhunterInterviewModel(
      id: json['id'] as int? ?? 0,
      candidateId: json['candidate_id'] as int? ?? 0,
      scheduledAt: DateTime.tryParse(json['scheduled_at']?.toString() ?? '') ??
          DateTime.now(),
      mode: json['mode'] as String? ?? 'online',
      notes: json['notes'] as String? ?? '',
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'candidate_id': candidateId,
        'scheduled_at': scheduledAt.toIso8601String(),
        'mode': mode,
        'notes': notes,
      };
}

class LaunchpadProgrammeModel {
  final int id;
  final String title;
  final String description;
  final String category;
  final int hours;
  final int weeks;
  final bool offersReference;
  final bool offersQualification;

  LaunchpadProgrammeModel({
    required this.id,
    required this.title,
    required this.description,
    required this.category,
    required this.hours,
    required this.weeks,
    required this.offersReference,
    required this.offersQualification,
  });

  factory LaunchpadProgrammeModel.fromJson(Map<String, dynamic> json) {
    return LaunchpadProgrammeModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      description: json['description'] as String? ?? '',
      category: json['category'] as String? ?? '',
      hours: json['hours'] as int? ?? 0,
      weeks: json['weeks'] as int? ?? 0,
      offersReference: json['offers_reference'] as bool? ?? false,
      offersQualification: json['offers_qualification'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'description': description,
        'category': category,
        'hours': hours,
        'weeks': weeks,
        'offers_reference': offersReference,
        'offers_qualification': offersQualification,
      };
}

class LaunchpadTaskModel {
  final int id;
  final String title;
  final bool completed;
  final int estimatedHours;

  LaunchpadTaskModel({
    required this.id,
    required this.title,
    required this.completed,
    required this.estimatedHours,
  });

  factory LaunchpadTaskModel.fromJson(Map<String, dynamic> json) {
    return LaunchpadTaskModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      completed: json['completed'] as bool? ?? false,
      estimatedHours: json['estimated_hours'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'completed': completed,
        'estimated_hours': estimatedHours,
      };
}

class LaunchpadApplicationModel {
  final int id;
  final int programmeId;
  final String status;
  final List<LaunchpadTaskModel> tasks;
  final DateTime? submittedAt;

  LaunchpadApplicationModel({
    required this.id,
    required this.programmeId,
    required this.status,
    required this.tasks,
    this.submittedAt,
  });

  factory LaunchpadApplicationModel.fromJson(Map<String, dynamic> json) {
    final taskList = (json['tasks'] as List?)
            ?.map((e) => LaunchpadTaskModel.fromJson(e as Map<String, dynamic>))
            .toList() ??
        <LaunchpadTaskModel>[];
    return LaunchpadApplicationModel(
      id: json['id'] as int? ?? 0,
      programmeId: json['programme_id'] as int? ?? 0,
      status: json['status'] as String? ?? 'draft',
      tasks: taskList,
      submittedAt: json['submitted_at'] != null
          ? DateTime.tryParse(json['submitted_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'programme_id': programmeId,
        'status': status,
        'tasks': tasks.map((e) => e.toJson()).toList(),
        'submitted_at': submittedAt?.toIso8601String(),
      };
}

class LaunchpadInterviewModel {
  final int id;
  final int applicationId;
  final DateTime scheduledAt;
  final String interviewer;

  LaunchpadInterviewModel({
    required this.id,
    required this.applicationId,
    required this.scheduledAt,
    required this.interviewer,
  });

  factory LaunchpadInterviewModel.fromJson(Map<String, dynamic> json) {
    return LaunchpadInterviewModel(
      id: json['id'] as int? ?? 0,
      applicationId: json['application_id'] as int? ?? 0,
      scheduledAt: DateTime.tryParse(json['scheduled_at']?.toString() ?? '') ??
          DateTime.now(),
      interviewer: json['interviewer'] as String? ?? '',
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'application_id': applicationId,
        'scheduled_at': scheduledAt.toIso8601String(),
        'interviewer': interviewer,
      };
}

class AiSessionModel {
  final String id;
  final String tool;
  final String prompt;
  final String output;
  final DateTime createdAt;

  AiSessionModel({
    required this.id,
    required this.tool,
    required this.prompt,
    required this.output,
    required this.createdAt,
  });

  factory AiSessionModel.fromJson(Map<String, dynamic> json) {
    return AiSessionModel(
      id: json['id']?.toString() ?? '',
      tool: json['tool'] as String? ?? '',
      prompt: json['prompt'] as String? ?? '',
      output: json['output'] as String? ?? '',
      createdAt:
          DateTime.tryParse(json['created_at']?.toString() ?? '') ?? DateTime.now(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'tool': tool,
        'prompt': prompt,
        'output': output,
        'created_at': createdAt.toIso8601String(),
      };
}

class AiSubscriptionPlanModel {
  final int id;
  final String name;
  final String tier;
  final int monthlyCredits;

  AiSubscriptionPlanModel({
    required this.id,
    required this.name,
    required this.tier,
    required this.monthlyCredits,
  });

  factory AiSubscriptionPlanModel.fromJson(Map<String, dynamic> json) {
    return AiSubscriptionPlanModel(
      id: json['id'] as int? ?? 0,
      name: json['name'] as String? ?? '',
      tier: json['tier'] as String? ?? '',
      monthlyCredits: json['monthly_credits'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'tier': tier,
        'monthly_credits': monthlyCredits,
      };
}

class AiUserSubscriptionModel {
  final AiSubscriptionPlanModel? plan;
  final int creditsUsed;
  final int creditsRemaining;

  AiUserSubscriptionModel({
    required this.plan,
    required this.creditsUsed,
    required this.creditsRemaining,
  });

  factory AiUserSubscriptionModel.fromJson(Map<String, dynamic> json) {
    return AiUserSubscriptionModel(
      plan: json['plan'] != null
          ? AiSubscriptionPlanModel.fromJson(json['plan'] as Map<String, dynamic>)
          : null,
      creditsUsed: json['credits_used'] as int? ?? 0,
      creditsRemaining: json['credits_remaining'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'plan': plan?.toJson(),
        'credits_used': creditsUsed,
        'credits_remaining': creditsRemaining,
      };
}

class AiUsageAggregateModel {
  final int totalRuns;
  final int tokensUsed;
  final Map<String, int> runsByTool;

  AiUsageAggregateModel({
    required this.totalRuns,
    required this.tokensUsed,
    required this.runsByTool,
  });

  factory AiUsageAggregateModel.fromJson(Map<String, dynamic> json) {
    final toolMap = (json['runs_by_tool'] as Map?)?.map(
          (key, value) => MapEntry(key.toString(), value as int? ?? 0),
        ) ??
        <String, int>{};
    return AiUsageAggregateModel(
      totalRuns: json['total_runs'] as int? ?? 0,
      tokensUsed: json['tokens_used'] as int? ?? 0,
      runsByTool: toolMap,
    );
  }

  Map<String, dynamic> toJson() => {
        'total_runs': totalRuns,
        'tokens_used': tokensUsed,
        'runs_by_tool': runsByTool,
      };
}

class VolunteeringOpportunityModel {
  final int id;
  final String title;
  final String organisation;
  final String location;
  final bool remote;
  final String description;

  VolunteeringOpportunityModel({
    required this.id,
    required this.title,
    required this.organisation,
    required this.location,
    required this.remote,
    required this.description,
  });

  factory VolunteeringOpportunityModel.fromJson(Map<String, dynamic> json) {
    return VolunteeringOpportunityModel(
      id: json['id'] as int? ?? 0,
      title: json['title'] as String? ?? '',
      organisation: json['organisation'] as String? ?? '',
      location: json['location'] as String? ?? '',
      remote: json['remote'] as bool? ?? false,
      description: json['description'] as String? ?? '',
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'organisation': organisation,
        'location': location,
        'remote': remote,
        'description': description,
      };
}

class VolunteeringApplicationModel {
  final int id;
  final int opportunityId;
  final String status;
  final DateTime? submittedAt;

  VolunteeringApplicationModel({
    required this.id,
    required this.opportunityId,
    required this.status,
    this.submittedAt,
  });

  factory VolunteeringApplicationModel.fromJson(Map<String, dynamic> json) {
    return VolunteeringApplicationModel(
      id: json['id'] as int? ?? 0,
      opportunityId: json['opportunity_id'] as int? ?? 0,
      status: json['status'] as String? ?? 'draft',
      submittedAt: json['submitted_at'] != null
          ? DateTime.tryParse(json['submitted_at'].toString())
          : null,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'opportunity_id': opportunityId,
        'status': status,
        'submitted_at': submittedAt?.toIso8601String(),
      };
}

PaginatedResult<T> paginatedFromJson<T>(
  Map<String, dynamic> json,
  T Function(Map<String, dynamic>) mapper,
) {
  final dataList = (json['data'] as List?) ?? json['items'] as List? ?? [];
  return PaginatedResult(
    data: dataList.map((e) => mapper(e as Map<String, dynamic>)).toList(),
    currentPage: json['current_page'] as int?,
    lastPage: json['last_page'] as int?,
    total: json['total'] as int?,
  );
}

List<HeadhunterPipelineItemModel> groupPipelineItems(
  List<HeadhunterPipelineItemModel> items,
  String stage,
) {
  return items.where((i) => i.stage == stage).toList();
}
