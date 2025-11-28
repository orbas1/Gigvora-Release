import 'package:flutter/foundation.dart';

import '../models/application.dart';
import '../models/ats.dart';
import '../services/applications_service.dart';

class ApplicationsState extends ChangeNotifier {
  ApplicationsState(this.service);

  final ApplicationsService service;

  bool loading = false;
  String? error;
  List<JobApplication> applications = <JobApplication>[];
  JobApplication? selected;

  Future<void> loadApplications() async {
    loading = true;
    notifyListeners();
    try {
      applications = await service.fetchApplications();
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> selectApplication(int id) async {
    selected = await service.fetchApplication(id);
    notifyListeners();
  }

  Future<void> submit(Map<String, dynamic> body) async {
    await service.submitApplication(body);
    await loadApplications();
  }

  Future<void> withdraw(int id) async {
    await service.withdraw(id);
    await loadApplications();
  }

  Future<void> moveStage(int id, AtsStage stage) async {
    await service.moveStage(id, stage);
    await selectApplication(id);
  }
}
