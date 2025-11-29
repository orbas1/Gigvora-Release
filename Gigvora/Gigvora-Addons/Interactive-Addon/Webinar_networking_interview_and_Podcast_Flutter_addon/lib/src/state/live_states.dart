import 'package:flutter/foundation.dart';

enum LoadStatus { idle, loading, loaded, empty, error }

class ViewState<T> extends ChangeNotifier {
  ViewState({this.data});

  LoadStatus status = LoadStatus.idle;
  String? error;
  T? data;

  void setLoading() {
    status = LoadStatus.loading;
    notifyListeners();
  }

  void setData(T value) {
    data = value;
    status = LoadStatus.loaded;
    notifyListeners();
  }

  void setEmpty() {
    data = null;
    status = LoadStatus.empty;
    notifyListeners();
  }

  void setError(String message) {
    error = message;
    status = LoadStatus.error;
    notifyListeners();
  }
}
