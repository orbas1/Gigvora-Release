import 'package:flutter/foundation.dart';

import '../models/talent_ai_models.dart';
import '../services/talent_ai_api.dart';

mixin _GuardedNotifier on ChangeNotifier {
  bool loading = false;
  String? error;

  Future<void> guard(Future<void> Function() action) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      await action();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}

class HeadhunterState extends ChangeNotifier with _GuardedNotifier {
  final HeadhunterApi api;
  HeadhunterProfileModel? profile;
  List<HeadhunterMandateModel> mandates = [];
  HeadhunterMandateModel? activeMandate;
  List<HeadhunterPipelineItemModel> pipelineItems = [];
  Map<String, List<HeadhunterPipelineItemModel>> pipelineByStage = {};
  HeadhunterCandidateModel? candidate;
  List<HeadhunterInterviewModel> interviews = [];

  HeadhunterState(this.api);

  Future<void> loadProfile() async {
    await guard(() async {
      profile = await api.profile();
    });
  }

  Future<void> loadMandates({int page = 1, Map<String, dynamic>? filters}) async {
    await guard(() async {
      final result = await api.mandates(page: page, filters: filters);
      mandates = result.data;
    });
  }

  Future<void> loadMandate(int id) async {
    await guard(() async {
      activeMandate = await api.mandate(id);
    });
  }

  Future<void> loadPipeline(int mandateId) async {
    await guard(() async {
      pipelineItems = await api.pipeline(mandateId);
      pipelineByStage = _groupPipeline(pipelineItems);
    });
  }

  Future<void> movePipeline({
    required int mandateId,
    required int itemId,
    required String stage,
  }) async {
    await guard(() async {
      final updated = await api.movePipelineItem(
        mandateId: mandateId,
        itemId: itemId,
        stage: stage,
      );
      pipelineItems = pipelineItems
          .map((e) => e.id == updated.id ? updated : e)
          .toList();
      pipelineByStage = _groupPipeline(pipelineItems);
    });
  }

  Future<void> loadCandidate(int id) async {
    await guard(() async {
      candidate = await api.candidate(id);
      interviews = await api.interviews(id);
    });
  }

  Future<void> updateNotes(int candidateId, String notes) async {
    await guard(() async {
      await api.updateCandidateNotes(candidateId, notes);
      candidate = candidate?.copyWith(notes: notes);
    });
  }

  Map<String, List<HeadhunterPipelineItemModel>> _groupPipeline(
      List<HeadhunterPipelineItemModel> items) {
    final Map<String, List<HeadhunterPipelineItemModel>> grouped = {};
    for (final item in items) {
      grouped.putIfAbsent(item.stage, () => []).add(item);
    }
    return grouped;
  }
}

extension on HeadhunterCandidateModel {
  HeadhunterCandidateModel copyWith({String? notes}) => HeadhunterCandidateModel(
        id: id,
        name: name,
        email: email,
        stage: stage,
        notes: notes ?? this.notes,
      );
}

class LaunchpadState extends ChangeNotifier with _GuardedNotifier {
  final LaunchpadApi api;
  List<LaunchpadProgrammeModel> programmes = [];
  LaunchpadProgrammeModel? selectedProgramme;
  LaunchpadApplicationModel? activeApplication;
  List<LaunchpadTaskModel> tasks = [];

  LaunchpadState(this.api);

  Future<void> loadProgrammes({int page = 1, Map<String, dynamic>? filters}) async {
    await guard(() async {
      final result = await api.programmes(page: page, filters: filters);
      programmes = result.data;
    });
  }

  Future<void> loadProgramme(int id) async {
    await guard(() async {
      selectedProgramme = await api.programme(id);
      tasks = await api.tasks(id);
    });
  }

  Future<void> apply(int programmeId, Map<String, dynamic> payload) async {
    await guard(() async {
      activeApplication = await api.apply(programmeId, payload: payload);
    });
  }

  Future<void> loadApplication(int id) async {
    await guard(() async {
      activeApplication = await api.application(id);
    });
  }

  Future<void> updateTask(int applicationId, int taskId, bool completed) async {
    await guard(() async {
      activeApplication = await api.updateTask(applicationId, taskId, completed);
      tasks = activeApplication?.tasks ?? tasks;
    });
  }
}

class AiWorkspaceState extends ChangeNotifier with _GuardedNotifier {
  final AiWorkspaceApi api;
  String activeTool = 'cv-writer';
  AiSessionModel? lastSession;
  List<AiSessionModel> sessions = [];
  AiUsageAggregateModel? usage;
  AiUserSubscriptionModel? subscription;

  AiWorkspaceState(this.api);

  Future<void> loadOverview() async {
    await guard(() async {
      sessions = await api.sessions();
      usage = await api.usage();
      subscription = await api.subscription();
    });
  }

  Future<void> runTool(String tool, Map<String, dynamic> payload) async {
    activeTool = tool;
    await guard(() async {
      lastSession = await api.runTool(tool, payload);
      sessions = [lastSession!, ...sessions];
    });
  }
}

class VolunteeringState extends ChangeNotifier with _GuardedNotifier {
  final VolunteeringApi api;
  List<VolunteeringOpportunityModel> opportunities = [];
  VolunteeringOpportunityModel? selected;
  List<VolunteeringApplicationModel> applications = [];
  VolunteeringApplicationModel? activeApplication;

  VolunteeringState(this.api);

  Future<void> loadOpportunities({int page = 1, Map<String, dynamic>? filters}) async {
    await guard(() async {
      final result = await api.opportunities(page: page, filters: filters);
      opportunities = result.data;
    });
  }

  Future<void> loadOpportunity(int id) async {
    await guard(() async {
      selected = await api.opportunity(id);
    });
  }

  Future<void> apply(int id, Map<String, dynamic> payload) async {
    await guard(() async {
      activeApplication = await api.apply(id, payload: payload);
      applications = await api.myApplications();
    });
  }

  Future<void> loadApplications() async {
    await guard(() async {
      applications = await api.myApplications();
    });
  }

  Future<void> loadApplication(int id) async {
    await guard(() async {
      activeApplication = await api.application(id);
    });
  }
}
