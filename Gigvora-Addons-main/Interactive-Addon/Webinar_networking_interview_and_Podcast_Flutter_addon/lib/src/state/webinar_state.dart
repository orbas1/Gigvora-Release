import 'package:flutter/foundation.dart';

import '../models/recording.dart';
import '../models/webinar.dart';
import '../models/webinar_registration.dart';
import '../services/webinar_service.dart';
import 'live_states.dart';

class WebinarState extends ChangeNotifier {
  WebinarState(this.service);

  final WebinarService service;

  final ViewState<List<Webinar>> upcoming = ViewState(data: []);
  final ViewState<List<Webinar>> myWebinars = ViewState(data: []);
  final ViewState<List<Recording>> recordings = ViewState(data: []);
  final ViewState<Webinar> selected = ViewState();
  WebinarRegistration? registration;

  Future<void> loadUpcoming() async {
    try {
      upcoming.setLoading();
      final response = await service.fetchUpcoming();
      final items = response.data;
      if (items.isEmpty) {
        upcoming.setEmpty();
      } else {
        upcoming.setData(items);
      }
    } catch (error) {
      upcoming.setError(error.toString());
    }
  }

  Future<void> loadAll() async {
    try {
      myWebinars.setLoading();
      final response = await service.fetchAll();
      if (response.data.isEmpty) {
        myWebinars.setEmpty();
      } else {
        myWebinars.setData(response.data);
      }
    } catch (error) {
      myWebinars.setError(error.toString());
    }
  }

  Future<void> loadRecordings(int webinarId) async {
    try {
      recordings.setLoading();
      final items = await service.fetchRecordings(webinarId);
      if (items.isEmpty) {
        recordings.setEmpty();
      } else {
        recordings.setData(items);
      }
    } catch (error) {
      recordings.setError(error.toString());
    }
  }

  Future<void> selectWebinar(int id) async {
    try {
      selected.setLoading();
      final webinar = await service.fetchDetail(id);
      selected.setData(webinar);
    } catch (error) {
      selected.setError(error.toString());
    }
  }

  Future<void> registerForWebinar(int id) async {
    try {
      registration = await service.register(id);
      notifyListeners();
    } catch (error) {
      selected.setError(error.toString());
    }
  }
}
