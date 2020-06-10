<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
    <title>{{ config('app.name', 'Cloud PBX') }}</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <!--<![endif]-->

    <style type="text/css" id="media-query">
      body {
      margin: 0;
      padding: 0; }

    table, tr, td {
      vertical-align: top;
      border-collapse: collapse; }

    .ie-browser table, .mso-container table {
      table-layout: fixed; }

    * {
      line-height: inherit; }

    a[x-apple-data-detectors=true] {
      color: inherit !important;
      text-decoration: none !important; }

    [owa] .img-container div, [owa] .img-container button {
      display: block !important; }

    [owa] .fullwidth button {
      width: 100% !important; }

    [owa] .block-grid .col {
      display: table-cell;
      float: none !important;
      vertical-align: top; }

    .ie-browser .num12, .ie-browser .block-grid, [owa] .num12, [owa] .block-grid {
      width: 600px !important; }

    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
      line-height: 100%; }

    .ie-browser .mixed-two-up .num4, [owa] .mixed-two-up .num4 {
      width: 200px !important; }

    .ie-browser .mixed-two-up .num8, [owa] .mixed-two-up .num8 {
      width: 400px !important; }

    .ie-browser .block-grid.two-up .col, [owa] .block-grid.two-up .col {
      width: 300px !important; }

    .ie-browser .block-grid.three-up .col, [owa] .block-grid.three-up .col {
      width: 200px !important; }

    .ie-browser .block-grid.four-up .col, [owa] .block-grid.four-up .col {
      width: 150px !important; }

    .ie-browser .block-grid.five-up .col, [owa] .block-grid.five-up .col {
      width: 120px !important; }

    .ie-browser .block-grid.six-up .col, [owa] .block-grid.six-up .col {
      width: 100px !important; }

    .ie-browser .block-grid.seven-up .col, [owa] .block-grid.seven-up .col {
      width: 85px !important; }

    .ie-browser .block-grid.eight-up .col, [owa] .block-grid.eight-up .col {
      width: 75px !important; }

    .ie-browser .block-grid.nine-up .col, [owa] .block-grid.nine-up .col {
      width: 66px !important; }

    .ie-browser .block-grid.ten-up .col, [owa] .block-grid.ten-up .col {
      width: 60px !important; }

    .ie-browser .block-grid.eleven-up .col, [owa] .block-grid.eleven-up .col {
      width: 54px !important; }

    .ie-browser .block-grid.twelve-up .col, [owa] .block-grid.twelve-up .col {
      width: 50px !important; }

    @media only screen and (min-width: 620px) {
      .block-grid {
        width: 600px !important; }
      .block-grid .col {
        vertical-align: top; }
        .block-grid .col.num12 {
          width: 600px !important; }
      .block-grid.mixed-two-up .col.num4 {
        width: 200px !important; }
      .block-grid.mixed-two-up .col.num8 {
        width: 400px !important; }
      .block-grid.two-up .col {
        width: 300px !important; }
      .block-grid.three-up .col {
        width: 200px !important; }
      .block-grid.four-up .col {
        width: 150px !important; }
      .block-grid.five-up .col {
        width: 120px !important; }
      .block-grid.six-up .col {
        width: 100px !important; }
      .block-grid.seven-up .col {
        width: 85px !important; }
      .block-grid.eight-up .col {
        width: 75px !important; }
      .block-grid.nine-up .col {
        width: 66px !important; }
      .block-grid.ten-up .col {
        width: 60px !important; }
      .block-grid.eleven-up .col {
        width: 54px !important; }
      .block-grid.twelve-up .col {
        width: 50px !important; } }

    @media (max-width: 620px) {
      .block-grid, .col {
        min-width: 320px !important;
        max-width: 100% !important;
        display: block !important; }
      .block-grid {
        width: calc(100% - 40px) !important; }
      .col {
        width: 100% !important; }
        .col > div {
          margin: 0 auto; }
      img.fullwidth, img.fullwidthOnMobile {
        max-width: 100% !important; }
      .no-stack .col {
        min-width: 0 !important;
        display: table-cell !important; }
      .no-stack.two-up .col {
        width: 50% !important; }
      .no-stack.mixed-two-up .col.num4 {
        width: 33% !important; }
      .no-stack.mixed-two-up .col.num8 {
        width: 66% !important; }
      .no-stack.three-up .col.num4 {
        width: 33% !important; }
      .no-stack.four-up .col.num3 {
        width: 25% !important; }
      .mobile_hide {
        min-height: 0px;
        max-height: 0px;
        max-width: 0px;
        display: none;
        overflow: hidden;
        font-size: 0px; } }
      .btn, .btn-xs{
        color: #ffffff;
        background-color: #0E3721;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        max-width: 230px;
        width: auto;
        border-top: 0px solid transparent;
        border-right: 0px solid transparent;
        border-bottom: 0px solid transparent;
        border-left: 0px solid transparent;
        font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;
        text-align: center;
        mso-border-alt: none;
        text-decoration: none;
      }
      .btn{
        width: 200px;
        padding-top: 15px;
        padding-right: 15px;
        padding-bottom: 15px;
        padding-left: 15px;
      }
      .btn-xs{
        /*width: 200px;*/
        padding-top: 5px;
        padding-right: 5px;
        padding-bottom: 5px;
        padding-left: 5px;
      }
      .btn-link{
        font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;
        text-decoration: none;
        color: black;
      }
      .btn span{
        font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;
        font-size:16px;
        line-height:32px;
      }
      .table-responsive{padding: 15px; background: #f2f2f2; min-height: .01%; overflow-x: auto;}
      .table{width: 100%; padding: 15px; background: #f2f2f2;}
      .table>tbody>tr>td {
      vertical-align: top;
      border-collapse: collapse;
      border-top: 1px solid #ddd;
      line-height: 1.4285;
      padding: 10px 5px;
      }
      .table>tbody>tr:nth-child(1)>td {
      vertical-align: top;
      border-collapse: collapse;
      border-top: 0px solid #ddd;
      line-height: 1.4285;
      padding: 10px 5px;
      }
      .horizonal-line{background: #51BB8D; !important; height: 2px; margin: 10px 0px; clear: both;}
      .m-5{margin: 5px;}
      .m-10{margin: 10px;}
      .m-15{margin: 15px;}
    </style>
    @yield('extra-css')
</head>
<body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #e2eace">
  <style type="text/css" id="media-query-bodytag">
    @media (max-width: 520px) {
      .block-grid {
        min-width: 320px!important;
        max-width: 100%!important;
        width: 100%!important;
        display: block!important;
      }

      .col {
        min-width: 320px!important;
        max-width: 100%!important;
        width: 100%!important;
        display: block!important;
      }

        .col > div {
          margin: 0 auto;
        }

      img.fullwidth {
        max-width: 100%!important;
      }
      img.fullwidthOnMobile {
        max-width: 100%!important;
      }
      .no-stack .col {
        min-width: 0!important;
        display: table-cell!important;
      }
      .no-stack.two-up .col {
        width: 50%!important;
      }
      .no-stack.mixed-two-up .col.num4 {
        width: 33%!important;
      }
      .no-stack.mixed-two-up .col.num8 {
        width: 66%!important;
      }
      .no-stack.three-up .col.num4 {
        width: 33%!important;
      }
      .no-stack.four-up .col.num3 {
        width: 25%!important;
      }
      .mobile_hide {
        min-height: 0px!important;
        max-height: 0px!important;
        max-width: 0px!important;
        display: none!important;
        overflow: hidden!important;
        font-size: 0px!important;
      }

    }
  </style>

    <table class="nl-container" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;margin: 0 auto;background-color: #e2eace;width: 100%" cellpadding="0" cellspacing="0">
      <tbody>
        <tr style="vertical-align: top">
          <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
            <div style="background-color:transparent;">
              <div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
                <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                    <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                      <div style="background-color: transparent; width: 100% !important;">
                        <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">

                            <div align="center" class="img-container center  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;">

                              <div style="line-height:25px;font-size:1px">&nbsp;
</div>  <img class="center  autowidth  fullwidth" align="center" border="0" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/20/rounder-up.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 600px" width="600">
                            </div>


                          </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div style="background-color:transparent;">
              <div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                  <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                    <div style="background-color: transparent; width: 100% !important;">
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                          <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;">
                            <img class="center  autowidth " align="center" border="0" src="{{ asset('images/logo.jpeg') }}" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 120px" width="120">
                          </div>
                          <div class="">
                            <div style="color:#555555;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;line-height:150%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                              <div style="font-size:12px;line-height:18px;color:#555555;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;"><p style="margin: 0;font-size: 16px;line-height: 21px;text-align: center">{{ config('app.name', 'Cloud PBX') }} </p></div>
                            </div>

                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div style="background-color:transparent;">
              <div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;" class="block-grid ">
                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
                  <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                    <div style="background-color: transparent; width: 100% !important;">
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                        <div class="">
                          <div style="color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">
                            <div style="font-size:12px;line-height:14px;color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;"><p style="margin: 0;font-size: 14px;line-height: 17px;text-align: center"><span style="font-size: 28px; line-height: 33px;"><strong><span style="line-height: 33px; font-size: 28px;"> @yield('greetings') </span></strong></span><br><span style="font-size: 28px; line-height: 33px;">@yield('title')</span></p></div>
                          </div>
                        </div>

                          <div align="center" class="img-container center  autowidth  " style="padding-right: 0px;  padding-left: 0px;">
                            <img class="center  autowidth " align="center" border="0" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/20/divider.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 316px" width="316">
                          </div>

                          <div class="">
                            <div style="color:#0D0D0D;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;line-height:150%; padding-right: 10px; padding-left: 10px; padding-top: 20px; padding-bottom: 10px;">

                              @yield('body')

                            </div>
                          </div>

                          <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                              <tbody>
                                  <tr style="vertical-align: top">
                                      <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 10px;padding-left: 10px;padding-top: 30px;padding-bottom: 10px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                          <table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 0px solid transparent;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                              <tbody>
                                                  <tr style="vertical-align: top">
                                                      <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                                          <span></span>
                                                      </td>
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>

                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div style="background-color:transparent;">
              <div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #525252;" class="block-grid three-up ">
                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#525252;">
                  <div class="col num4" style="max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;">
                    <div style="background-color: transparent; width: 100% !important;">
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">

                        <div align="center" style="padding-right: 0px; padding-left: 0px; padding-bottom: 0px;" class="">
                          <div style="line-height:15px;font-size:1px">&nbsp;
</div>
                          <div style="display: table; max-width:131px;">

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col num4" style="max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;">
                    <div style="background-color: transparent; width: 100% !important;">
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                        <div class="">
                          <div style="color:#a8bf6f;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;line-height:120%; padding-right: 0px; padding-left: 0px; padding-top: 20px; padding-bottom: 0px;">
                            <div style="font-size:12px;line-height:14px;color:#a8bf6f;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;"><p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center"><span style="color: rgb(255, 255, 255); font-size: 12px; line-height: 14px;"><span style="font-size: 12px; line-height: 14px; color: rgb(168, 191, 111);">Tel.:</span> 070 8319 1132 </span><br></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col num4" style="max-width: 320px;min-width: 200px;display: table-cell;vertical-align: top;">
                    <div style="background-color: transparent; width: 100% !important;">
                    <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                        <div class="">
                          <div style="color:#a8bf6f;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;line-height:120%; padding-right: 0px; padding-left: 0px; padding-top: 20px; padding-bottom: 0px;">
                            <div style="font-size:12px;line-height:14px;color:#a8bf6f;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;text-align:left;"><p style="margin: 0;font-size: 12px;line-height: 14px;text-align: center">Email <span style="color: rgb(255, 255, 255); font-size: 12px; line-height: 14px;">support@techmadeeazy.com</span></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div style="background-color:transparent;">
              <div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;" class="block-grid ">
                <div style="border-collapse: collapse;display: table;width: 100%;background-color:transparent;">
                  <div class="col num12" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
                    <div style="background-color: transparent; width: 100% !important;">
                      <div style="border-top: 0px solid transparent; border-left: 0px solid transparent; border-bottom: 0px solid transparent; border-right: 0px solid transparent; padding-top:0px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                        <div align="center" class="img-container center  autowidth  fullwidth " style="padding-right: 0px;  padding-left: 0px;">
                          <img class="center  autowidth  fullwidth" align="center" border="0" src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/20/rounder-dwn.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;width: 100%;max-width: 600px" width="600">
                        </div>
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="divider " style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 100%;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                          <tbody>
                            <tr style="vertical-align: top">
                              <td class="divider_inner" style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding-right: 30px;padding-left: 30px;padding-top: 30px;padding-bottom: 30px;min-width: 100%;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                  <table class="divider_content" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 0px solid transparent;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                      <tbody>
                                          <tr style="vertical-align: top">
                                              <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
                                                  <span></span>
                                              </td>
                                          </tr>
                                      </tbody>
                                  </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>