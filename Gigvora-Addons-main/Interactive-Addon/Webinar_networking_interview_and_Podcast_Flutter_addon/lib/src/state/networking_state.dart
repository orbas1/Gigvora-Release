import 'package:flutter/foundation.dart';

import '../models/networking_participant.dart';
import '../models/networking_session.dart';
import '../services/networking_service.dart';
import 'live_states.dart';

class NetworkingState extends ChangeNotifier {
  NetworkingState(this.service);

  final NetworkingService service;

  final ViewState<List<NetworkingSession>> sessions = ViewState(data: []);
  final ViewState<NetworkingSession> selected = ViewState();
  NetworkingParticipant? participant;

  Future<void> loadSessions() async {
    try {
      sessions.setLoading();
      final response = await service.fetchSessions();
      final data = response.data;
      if (data.isEmpty) {
        sessions.setEmpty();
      } else {
        sessions.setData(data);
      }
    } catch (error) {
      sessions.setError(error.toString());
    }
  }

  Future<void> loadDetail(int id) async {
    try {
      selected.setLoading();
      final detail = await service.fetchDetail(id);
      selected.setData(detail);
    } catch (error) {
      selected.setError(error.toString());
    }
  }

  Future<void> register(int id) async {
    try {
      participant = await service.register(id);
      notifyListeners();
    } catch (error) {
      selected.setError(error.toString());
    }
  }
}
