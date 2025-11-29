import 'package:flutter/foundation.dart';

import '../data/jobs_repository.dart';
import '../models/models.dart';

class ApplicationNotifier extends ChangeNotifier {
  ApplicationNotifier(this._repository);

  final JobsRepository _repository;
  List<JobApplication> applications = <JobApplication>[];
  AtsPipeline? pipeline;
  bool loading = false;
  String? error;

  Future<void> loadApplications({int? companyId}) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      applications = await _repository.fetchApplications(companyId: companyId);
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> fetchPipeline(int jobId) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      pipeline = await _repository.fetchPipeline(jobId);
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> advanceStage({required int applicationId, required int stageId}) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      final updated = await _repository.moveToStage(
        applicationId: applicationId,
        stageId: stageId,
      );
      applications = applications
          .map((app) => app.id == updated.id ? updated : app)
          .toList();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }
}
