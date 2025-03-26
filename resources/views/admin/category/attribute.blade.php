      <!-- Bordered table start -->
      <div class="row" id="table-bordered">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                      <h4 class="card-title">Attributes</h4>
                  </div>
                  <div class="card-body">
                      <p class="card-text">
                          Provide the attribute of categories below.
                          <code>For eg :- Mobile has RAM, Battery</code>
                      </p>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered">
                          <thead>
                              <tr>
                                  <th>S.n</th>
                                  <th>Attribute</th>
                                  <th>Help Text</th>
                                  <th>Value</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>1</td>
                                  <td>
                                      <input type="text" class="form-control form-control-sm" name="attribute[]"
                                          required>
                                  </td>
                                  <td>
                                      <input type="text" class="form-control form-control-sm" name="helpText[]">
                                  </td>
                                  <td>
                                      <input type="text" class="form-control form-control-sm" name="value[]" required>
                                  </td>
                                  <td><button class="btn btn-sm btn-outline-danger text-nowrap px-1"
                                          data-repeater-delete="" type="button"><svg xmlns="http://www.w3.org/2000/svg"
                                              width="16" height="16" fill="currentColor" class="bi bi-x-lg"
                                              viewBox="0 0 16 16">
                                              <path fill-rule="evenodd"
                                                  d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z">
                                              </path>
                                              <path fill-rule="evenodd"
                                                  d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z">
                                              </path>
                                          </svg><span>Delete</span></button></td>
                              </tr>
                          </tbody>
                          <tfoot>
                              <tr>
                                  <td colspan="8">
                                      <button class="btn btn-sm  btn-primary" type="button" id="#addNew"><svg
                                              xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                              stroke-linecap="round" stroke-linejoin="round"
                                              class="feather feather-plus me-25">
                                              <line x1="12" y1="5" x2="12" y2="19"></line>
                                              <line x1="5" y1="12" x2="19" y2="12"></line>
                                          </svg>
                                          <span>Add</span></button>
                                  </td>
                              </tr>
                          </tfoot>
                      </table>
                  </div>
              </div>
          </div>
      </div>
      <!-- Bordered table end -->

      @push('script')
      @endpush
