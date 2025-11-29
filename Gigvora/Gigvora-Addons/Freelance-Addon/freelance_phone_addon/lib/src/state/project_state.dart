import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/project.dart';
import '../models/proposal.dart';
import '../services/project_service.dart';

class ProjectListNotifier extends StateNotifier<AsyncValue<List<Project>>> {
  ProjectListNotifier(this.service) : super(const AsyncValue.loading());
  final ProjectService service;

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      state = AsyncValue.data(await service.browseProjects());
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}

class ProposalNotifier extends StateNotifier<AsyncValue<Proposal?>> {
  ProposalNotifier(this.service) : super(const AsyncValue.data(null));
  final ProjectService service;

  Future<void> submit(int projectId, Map<String, dynamic> payload) async {
    state = const AsyncValue.loading();
    try {
      final proposal = await service.submitProposal(projectId, payload);
      state = AsyncValue.data(proposal);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}
