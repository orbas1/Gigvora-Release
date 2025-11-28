import 'package:flutter/foundation.dart';

import '../models/job.dart';
import '../services/jobs_service.dart';

class JobsState extends ChangeNotifier {
  JobsState(this.service);

  final JobsService service;

  bool loading = false;
  String? error;
  List<Job> jobs = <Job>[];
  List<Job> saved = <Job>[];

  Future<void> loadJobs({String? keyword, String? location}) async {
    loading = true;
    error = null;
    notifyListeners();
    try {
      jobs = await service.fetchJobs(keyword: keyword, location: location);
    } catch (e) {
      error = e.toString();
    } finally {
      loading = false;
      notifyListeners();
    }
  }

  Future<void> loadSaved() async {
    try {
      saved = await service.fetchSavedJobs();
      notifyListeners();
    } catch (e) {
      error = e.toString();
    }
  }

  Future<void> toggleSave(Job job) async {
    final exists = saved.any((j) => j.id == job.id);
    if (exists) {
      saved.removeWhere((j) => j.id == job.id);
    } else {
      saved.add(job);
    }
    notifyListeners();
    await service.toggleSave(job.id, !exists);
  }
}
